<?php

namespace App\Helpers;

use Exception;

class test
{

    const DELETE_METING = 1;

    private $templateCode;
    private $params;

    private $templateData = [
        self::DELETE_METING => [
            'theme' => 'delete meeting',
            'data' => [
                'text' => 'Встреча: $meetingName была удалена $data',
                'btnText' => 'Связаться с модератором: $meetingName123'
            ]
        ],
    ];

    public function __construct($templateCode)
    {
        $this->templateCode = $templateCode;

        if (!array_key_exists($this->templateCode, $this->templateData)) {
            throw new Exception('Key not found');
        }

    }

    public function withParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function getData()
    {
        return [
            'theme' => $this->getTemplateTheme(),
            'data' => $this->getTemplateData()
        ];
    }

    private function getTemplateTheme()
    {
        return $this->templateData[$this->templateCode]['theme'];
    }

    private function getTemplateData()
    {
        $params = $this->params;
        $data = $this->templateData[$this->templateCode];
        return $this->replaceDynamicParams($data, $params);
    }

    public function replaceDynamicParams($data, $params)
    {
        return array_map(function ($item) use ($params) {
            return strtr($item, $params);
        }, $data['data']);
    }
    //hello
}
















