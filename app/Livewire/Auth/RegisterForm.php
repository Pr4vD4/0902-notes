<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RegisterForm extends Component
{
    public $name = '';
    public $phone = '';
    public $login = '';
    public $password = '';
    public $password_confirmation = '';
    public $agreement = false;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'regex:/^[а-яёА-ЯЁ\s-]+$/u'
            ],
            'phone' => [
                'required',
                'size:11',
                'unique:users',
                'regex:/^[0-9]+$/'
            ],
            'login' => [
                'required',
                'min:3',
                'unique:users',
                'regex:/^[a-zA-Z0-9-]+$/'
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
            'agreement' => ['accepted']
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Введите ваше имя',
            'name.min' => 'Имя должно содержать минимум 2 символа',
            'name.regex' => 'Имя может содержать только кириллицу',

            'phone.required' => 'Введите номер телефона',
            'phone.size' => 'Номер телефона должен содержать 11 цифр',
            'phone.unique' => 'Этот номер телефона уже зарегистрирован',
            'phone.regex' => 'Номер телефона должен содержать только цифры',

            'login.required' => 'Введите логин',
            'login.min' => 'Логин должен содержать минимум 3 символа',
            'login.unique' => 'Этот логин уже занят',
            'login.regex' => 'Логин может содержать только латиницу, цифры и тире',

            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.confirmed' => 'Пароли не совпадают',

            'agreement.accepted' => 'Необходимо согласие на обработку данных'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'login' => $validated['login'],
            'password' => Hash::make($validated['password'])
        ]);

        auth()->login($user);

        return $this->redirect('/diary', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
