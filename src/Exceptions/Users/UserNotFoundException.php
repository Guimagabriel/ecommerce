<?php

declare(strict_types=1);
namespace VirtualStore\Exceptions\Users;

class UserNotFoundException extends \Exception
{
  private const ERROR_MESSAGE = "Usuário inexistente ou senha inválida.";

  public function __construct(string $message = self::ERROR_MESSAGE, int $code = 0, ?Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
} 