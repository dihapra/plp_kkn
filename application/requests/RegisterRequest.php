<?php

class RegisterRequest
{
    public static function rules()
    {
        return [
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'],
        ];
    }
}
