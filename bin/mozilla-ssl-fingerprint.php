<?php
$ctx = stream_context_create(array('ssl' => array(
    'capture_peer_cert' => true,
    'verify_peer'       => true,
    'allow_self_signed' => false,
    'cafile'            => '/etc/pki/tls/certs/ca-bundle.crt',
    'CN_match'          => 'mxr.mozilla.org',
)));
$socket = stream_socket_client('ssl://mxr.mozilla.org:443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $ctx);
$params = stream_context_get_params($socket);
openssl_x509_export($params['options']['ssl']['peer_certificate'], $certificate);
$certificate = str_replace('-----BEGIN CERTIFICATE-----', '', $certificate);
$certificate = str_replace('-----END CERTIFICATE-----', '', $certificate);
$certificate = base64_decode($certificate);
$fingerprint = strtoupper(sha1($certificate));
$fingerprint = str_split($fingerprint, 2);
$fingerprint = implode(':', $fingerprint);
?>
<h2 style="text-align: center;">SSL SHA1 fingerprint for mxr.mozilla.org:</h2>
<pre style="text-align: center; font-size: 25px;"><?php echo $fingerprint; ?></pre>
<p style="text-align: center;">You are encouraged to <a href="https://github.com/EvanDotPro/Sslurp/blob/master/bin/mozilla-ssl-fingerprint.php">view the source of this file</a>. If you prefer additional verification, navigate to <a href="https://mxr.mozilla.org/">https://mxr.mozilla.org/</a> and view the certificate details in your own browser.</p>
