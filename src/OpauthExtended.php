<?php
/**
 * OpauthExtended.php
 *
 * @author Angel S. Moreno <angelxmoreno@gmail.com>
 * @copyright 2017 AngelXMoreno
 * @license http://www.spdx.org/licenses/MIT MIT License
 * @see https://github.com/angelxmoreno/slim-opauth/blob/master/README.md
 */

namespace SlimOpauth;

use Cake\Utility\Hash;

/**
 * Class OpauthExtended
 * @package SlimOpauth
 */
class OpauthExtended extends \Opauth
{
    /**
     * @return array
     */
    public function callbackExtended()
    {
        $response = $this->getResponse();

        $this->assertBody($response);
        $this->assertKeys($response);
        $this->validateBody($response);

        return $response;
    }


    /**
     * @param array $response
     * @throws OpauthExtendedException
     */
    protected function assertBody(array $response)
    {
        if (array_key_exists('error', $response)) {
            throw new OpauthExtendedException($response['error']);
        }
    }

    /**
     * @param array $response
     * @throws OpauthExtendedException
     */
    protected function assertKeys(array $response)
    {
        $expected_keys = [
            'auth.provider',
            'auth.uid',
            'timestamp',
            'signature',
        ];
        $flattened_array = Hash::flatten($response);

        foreach ($expected_keys as $key) {
            if (!array_key_exists($key, $flattened_array) || empty($flattened_array[$key])) {
                throw new OpauthExtendedException("Missing Opauth key: '{$key}'");
            }
        }
    }

    /**
     * @param array $response
     * @throws OpauthExtendedException
     */
    protected function validateBody(array $response)
    {
        $input = sha1(print_r($response['auth'], true));
        $timestamp = $response['timestamp'];
        $signature = $response['signature'];
        $error = false;

        $is_valid = $this->validate($input, $timestamp, $signature, $error);

        if (!$is_valid) {
            throw new OpauthExtendedException('Invalid Opauth body: ' . $error);
        }
    }

    /**
     * @return array
     * @throws OpauthExtendedException
     */
    protected function getResponse()
    {
        $response = false;

        switch ($this->env['callback_transport']) {
            case 'post':
                $response = $this->unpackResponse($_POST['opauth']);
                break;
            case 'get':
                $response = $this->unpackResponse($_GET['opauth']);
                break;
            default:
                throw new OpauthExtendedException("Unsupported callback_transport: '{$this->env['callback_transport']}'");
                break;
        }

        return $response;
    }

    /**
     * @param $string
     * @return array
     * @throws OpauthExtendedException
     */
    protected function unpackResponse($string)
    {
        $response = unserialize(base64_decode($string));

        if (!is_array($response)) {
            new OpauthExtendedException('Malformed opauth response');
        }

        return $response;
    }
}
