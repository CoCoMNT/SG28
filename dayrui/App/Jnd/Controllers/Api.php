<?php namespace Phpcmf\Controllers;

use Phpcmf\Common;
use Phpcmf\Service;
use Throwable;

class Api extends Common
{

    public function index(): void
    {
        $data=Service::L('cache')->get_data("canada_data");
        if(empty($data)){
            $this->SearchCanada();
            $data=Service::L('cache')->get_data("canada_data");
        }
        $issue = array();
        $time = array();
        $N1 = array();
        $N2 = array();
        $N3 = array();
        $N4 = array();
        for ($i = 0; $i < 5; $i++) {
            $issue[] = $data[$i]['issue'];
            $time[] = $data[$i]['time'];
            $N1[] = $data[$i]['n1'];
            $N2[] = $data[$i]['n2'];
            $N3[] = $data[$i]['n3'];
            $N4[] = $data[$i]['n4'];
        }
        var_dump($data);
        echo $this->calculateText("N1+N2+N3+N4", $N1, $N2, $N3, $N4, $issue);
    }

    private function calculateText(string $text, array $N1, array $N2, array $N3, array $N4, array $issue): string
    {
        $resultText = $text;

        // Mapping variables N1 to N48 with appropriate values from the arrays
        $mappings = [];
        for ($i = 0; $i < count($N1); $i++) {
            $mappings['N' . ($i * 4 + 1)] = $N1[$i] ?? 0;
            $mappings['N' . ($i * 4 + 2)] = $N2[$i] ?? 0;
            $mappings['N' . ($i * 4 + 3)] = $N3[$i] ?? 0;
            $mappings['N' . ($i * 4 + 4)] = $N4[$i] ?? 0;
        }

        // Define a helper function for "取首()", hypothetical implementation returning the first character
        $takeFirst = function ($value) {
            return is_string($value) && strlen($value) > 0 ? $value[0] : $value;
        };

        // Replace the "取首()" in the text
        $resultText = preg_replace_callback('/取首\((N\d+)\)/', function ($matches) use ($mappings, $takeFirst) {
            $key = $matches[1];
            return isset($mappings[$key]) ? $takeFirst($mappings[$key]) : 0;
        }, $resultText);

        // Perform the calculations in the text
        $resultText = preg_replace_callback('/(N\d+)/', function ($matches) use ($mappings) {
            $key = $matches[1];
            return $mappings[$key] ?? 0;
        }, $resultText);

        // Evaluate the final calculated string
        try {
            $result = eval('return ' . $resultText . ';');
        } catch (Throwable) {
            $result = 'Error in calculation';
        }

        return $result ?: 'Error in calculation';
    }
    private function SearchCanada(): void
    {
        $sql='SELECT * FROM `dr_canada_data` ORDER BY `issue` DESC LIMIT 0,3000';
        $canada = Service::M()->db->query($sql)->getResultArray();
        Service::L('cache')->set_data("canada_data", $canada,86400);
    }
}
