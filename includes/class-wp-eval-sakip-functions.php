<?php
class Esakip_Functions
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */


    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    function allow_access_private_post()
    {
        if (
            !empty($_GET)
            && !empty($_GET['key_sakip'])
        ) {
            $key = base64_decode($_GET['key_sakip']);
            $decode = $this->decode_key($_GET['key_sakip']);
            if (!empty($decode['skip'])) {
                return;
            }
            unset($_GET['key_sakip']);

            $key_db = md5(get_option(ESAKIP_APIKEY));
            $key = explode($key_db, $key);
            $valid = 0;
            if (
                !empty($key[1])
                && $key[0] == $key[1]
                && is_numeric($key[1])
            ) {
                $tgl1 = new DateTime();
                $date = substr($key[1], 0, strlen($key[1]) - 3);
                $tgl2 = new DateTime(date('Y-m-d', $date));
                $valid = $tgl2->diff($tgl1)->days + 1;
            }
            if ($valid == 1) {
                global $wp_query;
                // print_r($wp_query);
                // print_r($wp_query->queried_object); die('tes');
                if (!empty($wp_query->queried_object)) {
                    if ($wp_query->queried_object->post_status == 'private') {
                        wp_update_post(array(
                            'ID'    =>  $wp_query->queried_object->ID,
                            'post_status'   =>  'publish'
                        ));
                        $custom_post = get_page($wp_query->queried_object->ID);
                        $custom_post->custom_url = $_GET;
                        $link = $this->get_link_post($custom_post);
                        if (!empty($_GET['private'])) {
                            die('<script>window.location =  "' . $link . '";</script>');
                        } else {
                            die('<script>window.location =  "' . $link . '"+"&private=1";</script>');
                        }
                    } else if (!empty($_GET['private'])) {
                        wp_update_post(array(
                            'ID'    =>  $wp_query->queried_object->ID,
                            'post_status'   =>  'private'
                        ));
                    }
                } else if ($wp_query->found_posts >= 1) {
                    global $wpdb;
                    $sql = $wp_query->request;
                    $post = $wpdb->get_results($sql, ARRAY_A);
                    if (!empty($post)) {
                        if (empty($post[0]['post_status'])) {
                            return;
                        }
                        if ($post[0]['post_status'] == 'private') {
                            wp_update_post(array(
                                'ID'    =>  $post[0]['ID'],
                                'post_status'   =>  'publish'
                            ));
                            $custom_post = get_page($post[0]['ID']);
                            $custom_post->custom_url = $_GET;
                            $link = $this->get_link_post($custom_post);
                            if (!empty($_GET['private'])) {
                                die('<script>window.location =  "' . $link . '";</script>');
                            } else {
                                die('<script>window.location =  "' . $link . '"+"&private=1";</script>');
                            }
                        } else if (!empty($_GET['private'])) {
                            wp_update_post(array(
                                'ID'    =>  $post[0]['ID'],
                                'post_status'   =>  'private'
                            ));
                        }
                    }
                }
            }
        }
        if(is_404()){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".get_bloginfo('url'));
            exit();
        }
    }

    function gen_key($key_db = false, $options = array())
    {
        $now = time() * 1000;
        if (empty($key_db)) {
            $key_db = md5(get_option(ESAKIP_APIKEY));
        }
        $tambahan_url = '';
        $cek_param_get = [];
        if (!empty($options['custom_url'])) {
            $custom_url = array();
            foreach ($options['custom_url'] as $k => $v) {
                if(
                    !empty($v['key']) 
                    && !empty($v['value'])
                ){
                    $custom_url[] = $v['key'] . '=' . $v['value'];
                }else{
                    $cek_param_get[] = $k . '=' . $v;
                }
            }
            $tambahan_url = $key_db . implode('&', $custom_url);
        }
        $key = base64_encode($now . $key_db . $now . $tambahan_url);
        if(!empty($cek_param_get)){
            $key .= '&'.implode('&', $cek_param_get);
        }
        return $key;
    }

    public function decode_key($value)
    {
        $key = base64_decode($value);
        $key_db = md5(get_option(ESAKIP_APIKEY));
        $key = explode($key_db, $key);
        $get = array();
        if (!empty($key[2])) {
            $all_get = explode('&', $key[2]);
            foreach ($all_get as $k => $v) {
                $current_get = explode('=', $v);
                $get[$current_get[0]] = $current_get[1];
            }
        }
        return $get;
    }

    public function get_link_post($custom_post)
    {
        $link = get_permalink($custom_post->ID);
        $options = array();
        if (!empty($custom_post->custom_url)) {
            $options['custom_url'] = $custom_post->custom_url;
        }
        if (strpos($link, '?') === false) {
            $link .= '?key_sakip=' . $this->gen_key(false, $options);
        } else {
            $link .= '&key_sakip=' . $this->gen_key(false, $options);
        }
        return $link;
    }

    function get_page_by_title($page_title, $output = OBJECT, $post_type = 'page')
    {
        global $wpdb;
        if (is_array($post_type)) {
            $post_type = esc_sql($post_type);
            $post_type_in_string = "'" . implode("','", $post_type) . "'";
            $sql = $wpdb->prepare("
                SELECT 
                    ID,
                    post_status
                FROM $wpdb->posts
                WHERE post_title = %s
                    AND post_type IN ($post_type_in_string)
            ", $page_title);
        } else {
            $sql = $wpdb->prepare("
                SELECT 
                    ID,
                    post_status
                FROM $wpdb->posts
                WHERE post_title = %s
                    AND post_type = %s
            ", $page_title, $post_type);
        }
        $page = $wpdb->get_row($sql);
        if ($page) {
            // return get_post($page, $output);
            return $page;
        }
        return null;
    }


    public function generatePage($options = array())
    {
        $post_type = 'page';
        $status = 'private';
        if (!empty($options['post_status'])) {
            $status = $options['post_status'];
        }
        if (!empty($options['post_type'])) {
            $post_type = $options['post_type'];
        }

        if (!empty($options['post_id'])) {
            $custom_post = get_page($options['post_id']);
        } else {
            $custom_post = $this->get_page_by_title($options['nama_page'], OBJECT, $post_type);
        }
        $_post = array(
            'post_title'    => $options['nama_page'],
            'post_content'    => $options['content'],
            'post_type'        => $post_type,
            'post_status'    => $status,
            'comment_status'    => 'closed'
        );
        if (empty($custom_post) || empty($custom_post->ID)) {
            $id = wp_insert_post($_post);
            $_post['insert'] = 1;
            $_post['ID'] = $id;
            $custom_post = $this->get_page_by_title($options['nama_page'], OBJECT, $post_type);
            if (empty($options['show_header'])) {
                update_post_meta($custom_post->ID, 'ast-main-header-display', 'disabled');
                update_post_meta($custom_post->ID, 'footer-sml-layout', 'disabled');
            }
            update_post_meta($custom_post->ID, 'ast-breadcrumbs-content', 'disabled');
            update_post_meta($custom_post->ID, 'ast-featured-img', 'disabled');
            update_post_meta($custom_post->ID, 'site-content-layout', 'page-builder');
            update_post_meta($custom_post->ID, 'site-post-title', 'disabled');
            update_post_meta($custom_post->ID, 'site-sidebar-layout', 'no-sidebar');
            update_post_meta($custom_post->ID, 'theme-transparent-header-meta', 'disabled');
        } else if (!empty($options['update'])) {
            $_post['ID'] = $custom_post->ID;
            wp_update_post($_post);
            $_post['update'] = 1;
        }
        if (!empty($options['custom_url'])) {
            $custom_post->custom_url = $options['custom_url'];
        }
        if (!empty($options['no_key'])) {
            $link = get_permalink($custom_post->ID);
        } else {
            $link = $this->get_link_post($custom_post);
        }
        return array(
            'post' => $custom_post,
            'id' => $custom_post->ID,
            'title' => $options['nama_page'],
            'url' => $link
        );
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function CekNull($number, $length = 2)
    {
        $l = strlen($number);
        $ret = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i + 1 > $l) {
                $ret .= '0';
            }
        }
        $ret .= $number;
        return $ret;
    }

    function user_has_role($user_id, $role_name, $return = false)
    {
        if (empty($user_id)) {
            return false;
        }
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        if ($return) {
            return $user_roles;
        } else {
            return in_array($role_name, $user_roles);
        }
    }

    function get_option_complex($key, $type=0){
        global $wpdb;
        $ret = $wpdb->get_results('select option_name, option_value from '.$wpdb->prefix.'options where option_name like \''.$key.'|%\'', ARRAY_A);
        $res = array();
        $types = array();
        foreach($ret as $v){
            $k = explode('|', $v['option_name']);
            $column = $k[1];
            $group = $k[3];
            if($column == ''){
                $types[$group] = $v['option_value'];
            }
        }
        foreach($ret as $v){
            $k = explode('|', $v['option_name']);
            $column = $k[1]; // nama kolom atau field
            $loop = $k[2]; // index nomor urut sesuai group
            if($column != ''){
                if(empty($res[$loop])){
                    $res[$loop] = array();
                }
                $res[$loop][$column] = $v['option_value'];
            }
        }
        return $res;
    }

    function get_option_multiselect($key)
    {
        global $wpdb;
        $ret = $wpdb->get_results('select option_name, option_value from ' . $wpdb->prefix . 'options where option_name like \'' . $key . '|||%\'', ARRAY_A);
        $res = array();
        foreach ($ret as $v) {
            $res[$v['option_value']] = $v['option_value'];
        }
        return $res;
    }

    function isInteger($input)
    {
        return (ctype_digit(strval($input)));
    }

    function curl_post($options)
    {
        $curl = curl_init();
        set_time_limit(0);
        $url = $options['url'];
        if (empty($url)) {
            return false;
        }
        $opsi_curl = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 10000
        );
        if(!empty($options['type']) && strtolower($options['type']) != 'get'){
            $opsi_curl[CURLOPT_CUSTOMREQUEST] = 'POST';
            $req = http_build_query($options['data']);
            $opsi_curl[CURLOPT_POSTFIELDS] = $req;
        }else{
            $opsi_curl[CURLOPT_CUSTOMREQUEST] = 'GET';
        }

        if (!empty($options['header'])) {
            if(gettype($options['header']) == 'string'){
                $options['header'] = array($options['header']);
            }
            $opsi_curl[CURLOPT_HTTPHEADER] = $options['header'];
        }

        curl_setopt_array($curl, $opsi_curl);

        $response = curl_exec($curl);
        // print_r($opsi_curl); die($response);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $msg = "cURL Error #:" . $err . " (" . $url . ")";
            if (!empty($options['debug']) && $options['debug'] == 1) {
                die($msg);
            } else {
                return $msg;
            }
        } else {
            return $response;
        }
    }

    public static function uploadFile(
        string $api_key = '',
        string $path = '',
        array $file = array(),
        array $ext = array(),
        int $maxSize = 1048576, // default 1MB
        string $nama_file = ''
    ) {
        try {
            if (!empty($api_key) && $api_key == get_option(ESAKIP_APIKEY)) {
                if (!empty($file)) {
                    if (empty($ext)) {
                        throw new Exception('Extensi file belum ditentukan ' . json_encode($file));
                    }

                    if (empty($path)) {
                        throw new Exception('Lokasi folder belum ditentukan ' . json_encode($file));
                    }
                    
                    $imageFileType = strtolower(pathinfo($path . basename($file["name"]), PATHINFO_EXTENSION));
                    if (!in_array($imageFileType, $ext)) {
                        throw new Exception('Lampiran wajib ber-type ' . implode(", ", $ext) . ' ' . json_encode($file));
                    }

                    if ($file['size'] > $maxSize) {
                        throw new Exception('Ukuran file melebihi ukuran maksimal ' . json_encode($file));
                    }

                    if (!empty($nama_file)) {
                        // hapus extension file
                        $nama_file = explode('.', $nama_file);
                        $cek_jml = count($nama_file);
                        if($cek_jml >= 2){
                            unset($nama_file[$cek_jml-1]);
                        }
                        $nama_file = implode('.', $nama_file);

                        $file['name'] = $nama_file . '.' . $imageFileType;
                    } else {
                        $nama_file = date('Y-m-d-H-i-s');
                        $file['name'] = $nama_file . '-' . $file['name'];
                    }
                    $target = $path .  $file['name'];

                    if(file_exists($target)) {
                        throw new Exception("File dengan nama $target sudah ada!");
                    }

                    $moved = move_uploaded_file($file['tmp_name'], $target);
                    if ($moved) {
                        return [
                            'status' => true,
                            'filename' => $file['name']
                        ];
                    } else {
                        throw new Exception("Oops, gagal upload file " . $file['name'] . ", hubungi admin. Not uploaded because of error #" . $file["error"] . ' ' . json_encode($file));
                    }
                }
                throw new Exception('Oops, file belum dipilih');
            }
            throw new Exception('Api key tidak ditemukan');
        } catch (Exception $e) {
            return array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
    }

    function renameFile($oldName, $newName, $ext=array('pdf')) {
        if (empty($newName)) {
            return array(
                'status' => 'error',
                'message' => "Nama file tidak boleh kosong!"
            );
        }
        $fileInfo = pathinfo($newName);
        $ext_file = strtolower($fileInfo['extension']);
        if (!in_array($ext_file, $ext)) {
            return array(
                'status' => 'error',
                'message' => "Nama file harus berextension ".implode(', ', $ext)
            );
        }
        if(!file_exists($oldName)) {
            return array(
                'status' => 'error',
                'message' => "File tidak ditemukan: $oldName"
            );
        }else if(file_exists($newName)) {
            return array(
                'status' => 'error',
                'message' => "File dengan nama $newName sudah ada!"
            );
        }
        if(rename($oldName, $newName)) {
            return array(
                'status' => 'success',
                'message' => "File berhasil di-rename dari $oldName ke $newName"
            );
        } else {
            return array(
                'status' => 'error',
                'message' => "Gagal merename file $oldName ke $newName"
            );
        }
    }

    function getFullUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $fullUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $fullUrl;
    }

    function modifyGetParameter($url, $paramName, $paramValue) {
        if(empty($url)){
            $url = $this->getFullUrl();
        }
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'], $queryParams);
        $queryParams[$paramName] = $paramValue;
        $newQuery = http_build_query($queryParams);
        
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['port'])) {
            $newUrl .= ':' . $parsedUrl['port'];
        }
        $newUrl .= $parsedUrl['path'] . '?' . $newQuery;
        
        if (isset($parsedUrl['fragment'])) {
            $newUrl .= '#' . $parsedUrl['fragment'];
        }
        
        return $newUrl;
    }

    function add_param_get($url, $param){
        $data = explode('?', $url);
        if(count($data) > 1){
            $url .= $param;
        }else{
            $url .= '?'.$param;
        }
        return $url;
    }
    
    function login_to_other_site($opsi = array()){
        global $wpdb;
        $user_data = array(
            'login' => $opsi['user']->user_login,
            'time'  => time()
        );

        $payload = base64_encode(json_encode($user_data));
        $url_asli = '';
        if(!empty($opsi['url_asli'])){
            $url_asli = $opsi['url_asli'];
        }

        $url = '';
        if(!empty($opsi['domain'])){
            $signature = hash_hmac('sha256', $payload, $opsi['api_key']);
            $token = $payload . '.' . $signature;
            if (substr($opsi['domain'], -1) !== '/') {
                $opsi['domain'] .= '/';
            }
            $url = $opsi['domain'].'sso-login?token=' . urlencode($token).'&redirect='.$url_asli;
        }else if(!empty($opsi['id_login'])){
            $data = $this->get_option_complex('_crb_auto_login');
            $url = $url_asli;
            foreach($data as $v){
                if(
                    !empty($v['app_url'])
                    && !empty($v['api_key'])
                    && $v['id_login'] == $opsi['id_login']
                ){
                    $signature = hash_hmac('sha256', $payload, $v['api_key']);
                    $token = $payload . '.' . $signature;
                    if (substr($v['app_url'], -1) !== '/') {
                        $v['app_url'] .= '/';
                    }
                    $url = $v['app_url'].'sso-login?token=' . urlencode($token).'&redirect='.$url_asli;
                }
            }
        }
        return $url;
    }

    function handle_sso_login(){
        if (!is_page('sso-login') || !isset($_GET['token'])) return;

        $token = sanitize_text_field($_GET['token']);
        list($payload, $signature) = explode('.', $token);

        $expected_signature = hash_hmac('sha256', $payload, get_option(ESAKIP_APIKEY));

        $pesan_error = '';
        if (!hash_equals($expected_signature, $signature)) {
            $pesan_error = 'SSO token tidak valid.';
        }

        if(empty($pesan_error)){
            $data = json_decode(base64_decode($payload), true);
            if (!$data || !isset($data['login'])) {
                $pesan_error = 'Data SSO tidak lengkap.';
            }
        }

        if(empty($pesan_error)){
            // Cek kadaluwarsa token (misal 60 detik)
            if (time() - $data['time'] > 60) {
                $pesan_error = 'Token kadaluarsa.';
            }
        }

        if(empty($pesan_error)){
            $user = get_user_by('login', $data['login']);
            if (!$user) {
                $pesan_error = 'Pengguna '.$data['login'].' tidak ditemukan di '.site_url();
            }
        }

        if(!empty($pesan_error)){
            update_option('wp_sso_login', $pesan_error);
            wp_die($pesan_error);
        }

        // Login otomatis
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);

        update_option('wp_sso_login', 'Berhasil login '. $data['login'].' '.date('Y-m-d H:i:s'));

        if(!empty($_GET['redirect'])){
            wp_redirect($_GET['redirect']);
        }else{
            wp_redirect(site_url());
        }
        exit;
    }
}
