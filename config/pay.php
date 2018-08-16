<?php
return [ 
  'alipay' => [
        'app_id'         => '2016091700532476',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA32Xc/WqzL5t71dHC9AhgcvPTS9Upg6w+j8ElVqYysqI8i31G2yXuOT8XE6zUd78NdIg1jI7OA3BUY353dZGYh68EmE7E9p2coFp+t6GTYwAt/LwPn61qCfMzywdbr/bm6//K96rY+grNh/hmjgbXkANm67t38BggaNIJWvJnCBQvAaSBBVV9DrSCsUV+lGVyfJX5SCjOoLkHYDXM4H0wbssigmIFld+ah8G77d7axSBouwL5uzF4BKXh7RHIjCL3CKuqPuRq0et4GQwyXVhRR4kgdV0jv3dVQnL2wTSOxkCyo1ZrvyG9JMX6wcZ/Sy9BYbyBPez20I6Hkm2zfeRiLwIDAQAB',
        'private_key'    => 'MIIEogIBAAKCAQEA32Xc/WqzL5t71dHC9AhgcvPTS9Upg6w+j8ElVqYysqI8i31G2yXuOT8XE6zUd78NdIg1jI7OA3BUY353dZGYh68EmE7E9p2coFp+t6GTYwAt/LwPn61qCfMzywdbr/bm6//K96rY+grNh/hmjgbXkANm67t38BggaNIJWvJnCBQvAaSBBVV9DrSCsUV+lGVyfJX5SCjOoLkHYDXM4H0wbssigmIFld+ah8G77d7axSBouwL5uzF4BKXh7RHIjCL3CKuqPuRq0et4GQwyXVhRR4kgdV0jv3dVQnL2wTSOxkCyo1ZrvyG9JMX6wcZ/Sy9BYbyBPez20I6Hkm2zfeRiLwIDAQABAoIBAFFGOu8LeaNRWu1Agj/MKGLJ6E6Hf7IC4UPljhMHJhytP3+0FSo3k4LKPDgRQb9LB7cksu6vKa5AmMMVyJpCXQ7IEtDin2/liY0hP0bJhtiWwSRYuwppydFS5L3xsF7FYd/sUMhoJMZbVckpCw6S2KvxQOl0a78y19+qwnKPDAL4vto0htBKCpK+IZuwAhPCyj+j2zR4v9UAbuqwsyOeN558WMXm86go4SaOQiF3pP6g5XH1gAncJuQZQiwjhCJ1qhYr/SNzlQ9II7/0li9pJyLYbdF1KLLxolCcNHzeamEz5uQuhnn8B3PJoX39vJDMt0O6TpQBGvjTIdF3s1uU+DkCgYEA8KnjJ9LBrkTmPkgSPd5BkiSY6BDZ12n2djUzDSaPm3fc9fwKzd2WMkrVaEruNb04VqhiOVVeum2a3dNsclVuRMIICjk5CDR0d6H26krgANnJT2bQQuu+9phr1S0eeVZ7pOedeqNDSgy0DcI8mvvn2P77SSaKpGU86HGieiCvjfMCgYEA7aJO6g/VlWFhvB5ePDGYMGo3+3hlZE2zsoP4WMGGpQe5Jv4aYxiEDz7v8srk+rrcE6GUZLYiGYcFn4i48z29nOaIRl1A3qC3S0dlnRDFGUB0d/lVnRu34kOHHS+vv2Uona6bsruTMJ+ynHx4hjdoZA3UmI8gG5NriJh7uBw2XdUCgYATzsfFdVE/mKgipCfsM6jN8HktUIW9dxkz7XkAMhZLw3fKPy5cd0pvZT1fUht9DM/gQgejlGUxjufaLhaU6nci7Z1fygnppPeZCRmbewIoz8QD5wBpIaIcBbuKViZV8kpG7lFF0L4vEBCZuUznrKgOch4YjTWIBlUXBJNM3ffVBQKBgEaawveoXL1VgcRzAWzBD4bTCG8fBoEW1R1lOJSma2r9MHX1acGntxUQjhqFTiNxAZKTn5OMyfTruS/9X3ZmEcBcfoDplCyRc+RaAkb4hJdMH07GNjnH0orXpX12fbFQTREMoBYqxUDdMiRslyLJs6qGsXPikpee5YRgSeIZ/81BAoGAHNS7zYkZX5PJ3r0yA/g0GJw1BUcCtkLsn1dYRgHctw0fcsgYRhJ2Igy1T0FlF2kr4wBhg/pbKLQSIX0Ss85a8pBABI8wu8UHvL0E9IwinkH11Awwd+dnhCeNTaa3kW9lUjvPOW7ILSzR/VronWRF4s/4ofLMhWjQpXdEFgeNq4U=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
