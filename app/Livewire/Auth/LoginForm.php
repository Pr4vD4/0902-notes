<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public $login = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'login' => ['required', 'string'],
        'password' => ['required'],
    ];

    protected $messages = [
        'login.required' => 'Введите логин',
        'password.required' => 'Введите пароль',
    ];

    public function handleLogin()
    {
        $credentials = $this->validate();

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return $this->redirect(route('diary'), navigate: true);
        }

        $this->addError('login', 'Неверный логин или пароль');
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
