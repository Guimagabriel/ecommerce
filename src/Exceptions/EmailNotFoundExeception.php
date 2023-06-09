<?php

declare(strict_types=1);
namespace VirtualStore\Exceptions\Users;

class EmailNotFoundException extends \Exception
{
  private const ERROR_MESSAGE = "Não foi possível recuperar a senha.";

  public function __construct(string $message = self::ERROR_MESSAGE, int $code = 0, ?Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
} 