<?php

class ModelInstallDbinit extends Model {
    private $tables = [
        'url_redirect' => [
            'id' => 'INT(11) NOT NULL',
            'url_slug' => 'TEXT NOT NULL',
            'redirect' => 'TEXT NOT NULL',
            'method' => 'TEXT NULL',
            'note' => 'TEXT NULL'
        ],
        'users' => [
            'user_id' => 'INT(11) NOT NULL',
            'email' => 'VARCHAR(100) NOT NULL',
            'passcode' => 'VARCHAR(255) NOT NULL',
            'display_name' => 'VARCHAR(100) NOT NULL',
            'status' => 'INT(11) NOT NULL',
            'token' => 'VARCHAR(255) NOT NULL',
            'date_created' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'date_modified' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ],
        'team' => [
            'member_id' => 'INT(11) NOT NULL',
            'name' => 'VARCHAR(100) NOT NULL',
            'short_description' => 'TEXT NOT NULL',
            'description' => 'TEXT NULL',
            'url_slug' => 'VARCHAR(100) NULL',
//            'additional_data' => 'TEXT NULL',
            'active' => "INT(1) NOT NULL DEFAULT '0'",
            'image' => 'VARCHAR(256) NOT NULL',
            'background_image' => 'VARCHAR(256) NOT NULL'
        ],
        'user_status' => [
            'status_id' => 'INT(11) NOT NULL',
            'status_message' => 'VARCHAR(50) NOT NULL DEFAULT \'0\'',
            'description' => 'TEXT NULL'
        ],
        'settings' => [
            'settings_id' => 'INT NOT NULL',
            'code' => 'VARCHAR(50) NOT NULL',
            'key' => 'VARCHAR(50) NOT NULL',
            'data' => 'TEXT NOT NULL',
            'displayed' => 'TINYINT NOT NULL DEFAULT \'0\''
        ],
        'messages' => [
            'message_id' => 'INT NOT NULL',
            'name' => 'VARCHAR(20) NOT NULL',
            'email' => 'VARCHAR(100) NOT NULL',
            'telephone' => 'VARCHAR(20) NOT NULL',
            'message' => 'TEXT NOT NULL',
            'office' => 'INT(2) NOT NULL',
            'date' => 'DATETIME NOT NULL',
            'checked_by' => "INT(5) NULL DEFAULT NULL",
        ],
        'testimonials' => [
            'testimonial_id' => 'INT NOT NULL',
            'heading' => 'TEXT NOT NULL',
            'text' => 'TEXT NOT NULL',
            'active' => "INT(1) NOT NULL DEFAULT '0'",
            'image' => 'TEXT NOT NULL'
        ],
        'services' => [
            'service_id' => 'INT NOT NULL',
            'text' => 'TEXT NOT NULL',
            'description' => 'TEXT NOT NULL',
            'active' => "INT(1) NOT NULL DEFAULT '0'",
            'icon' => 'TEXT NOT NULL'
        ]
    ];

    public function createTables() {
        $this->chooseDB('MySQLi');

        foreach($this->tables as $t_name => $columns) {
            $messages[] = $this->createTable($t_name, $columns);
        }
        return isset($messages) ? $messages : null;
    }

    public function createTable($t_name, $columns) {
        $arr['name'] = $t_name;
        foreach ($columns as $name => $column) {
            $arr['cols'][$name] = $column;
        }
        if($this->db->create($arr)) {
            return 'Created table : "' . DB_PREFIX . $t_name . '" with ' . count($arr['cols']) . ' number of columns.';
        } else {
            return 'Failed to create table : "' . DB_PREFIX . $t_name . '". Please retry !';
        }
    }
}