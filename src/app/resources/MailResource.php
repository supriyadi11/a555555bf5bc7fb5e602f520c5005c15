<?php
namespace App\Resources;


class MailResource {

    public function __construct(private array $data) {}

    public function mapping() : array
    {
        $result = [
            'message' => 'Get data success!',
            'data' => $this->data['data'] ?? $this->data,
        ];

        if (in_array('per_page', array_keys($this->data))) {
            $result['meta'] = [
                'path' => request()->request['path'],
                'per_page' => $this->data['per_page'],
                'total_page' => $this->data['last_page'],
                'next_page_url' => $this->data['next_page_url'] ? request()->server['HTTP_HOST'].'/'.request()->request['path'].$this->data['next_page_url'] : null,
                'prev_page_url' => $this->data['prev_page_url'] ? request()->server['HTTP_HOST'].'/'.request()->request['path'].$this->data['prev_page_url'] : null,
                'total' => $this->data['total']
            ];
        }
        
        return $result;
    }
}