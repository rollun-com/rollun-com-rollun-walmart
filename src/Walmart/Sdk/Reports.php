<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

/**
 * Class Reports
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Reports extends Base
{
    const DATA_DIR = 'data';
    const ZIP_FILE_NAME = self::DATA_DIR . '/tmp_walmart_report.zip';

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getReport
     *
     * @param string $sku
     * @param string $shipNode
     *
     * @return array
     * @throws \Exception
     */
    public function getItemReport(): array
    {
        return $this->request("getReport?type=item&version=3");
    }

    /**
     * @param string $path
     * @param string $method
     * @param array  $data
     *
     * @return array
     * @throws \Exception
     */
    protected function request(string $path, string $method = 'GET', array $data = []): array
    {
        $ch = curl_init();

        // prepare headers
        $headers = [
            "WM_SVC.NAME: " . self::NAME,
            "WM_QOS.CORRELATION_ID: " . $this->correlationId,
            "WM_SVC.VERSION: " . self::VERSION,
            "WM_SEC.ACCESS_TOKEN: " . $this->getToken(),
            "Authorization: Basic " . $this->authHash,
            "Accept: application/xml"
        ];

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // create dir if not exists
        if (!file_exists(self::DATA_DIR)) {
            mkdir(self::DATA_DIR, 0777, true);
        }

        // save response zip
        $fp = fopen(self::ZIP_FILE_NAME, 'w');
        fwrite($fp, $response);
        fclose($fp);

        // prepare result
        $result = [];

        if (file_exists(self::ZIP_FILE_NAME)) {
            // prepare extract dir
            $extractDir = self::DATA_DIR . '/' . time();

            // unpack zip
            $zip = new \ZipArchive;
            if ($zip->open(self::ZIP_FILE_NAME) === true) {
                $zip->extractTo($extractDir);
                $zip->close();
            }

            // delete zip file
            unlink(self::ZIP_FILE_NAME);

            foreach (scandir($extractDir) as $fileName) {
                if (!in_array($fileName, ['.', '..'])) {
                    if (($handle = fopen("$extractDir/$fileName", "r")) !== false) {
                        while (($line = fgetcsv($handle, 999999, ",")) !== false) {
                            if (empty($columns)) {
                                $columns = [];
                                foreach ($line as $v) {
                                    $columns[] = strtolower(str_replace(' ', '_', $v));
                                }
                                continue 1;
                            }

                            $row = [];
                            foreach ($line as $k => $v) {
                                $row[$columns[$k]] = $v;
                            }

                            $result[] = $row;
                        }
                        fclose($handle);
                    }
                    // delete extracted file
                    unlink("$extractDir/$fileName");
                }
            }

            // delete extracted link
            rmdir($extractDir);
        }

        return $result;
    }
}
