<?php


namespace App\Form\Model;
use App\Validator\UniqueUser;

use Symfony\Component\Validator\Constraints as Assert;


class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter an email!")
     * @Assert\Email()
     * @UniqueUser()
     *
     */
    public $email;
    /**
     * @Assert\NotBlank(message ="Choose a password")
     * @Assert\Length(min=5, minMessage="This password is too short!")
     */
    public $plainPassword;
    /**
     * @Assert\IsTrue(message="Pleas, agree with terms!")
     */
    public $agreeTerms;
}