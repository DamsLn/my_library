<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    private const string NOT_BLANK_MESSAGE = 'Ce champ ne peut pas être vide';

    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: self::NOT_BLANK_MESSAGE
        ),
        new Assert\Email(
            message: 'Vous devez saisir une adresse email valide.',
        )
    ])]
    private string $email;

    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: self::NOT_BLANK_MESSAGE
        ),
        new Assert\Length(
            max: 255,
            maxMessage: 'L\'objet de votre demande doit contenir au maximum 255 caractères.'
        )
    ])]
    private string $subject;

    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: self::NOT_BLANK_MESSAGE
        ),
        new Assert\Length(
            min: 20,
            max: 1000,
            minMessage: 'Votre message doit contenir au moins 20 caractères.',
            maxMessage: 'Votre message doit contenir au maximum 1000 caractères.'
        )
    ])]
    private string $message;

    /**
     * @return string
     */ 
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */ 
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */ 
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return self
     */ 
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */ 
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */ 
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}