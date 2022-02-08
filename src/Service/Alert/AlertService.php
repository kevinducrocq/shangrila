<?php

namespace App\Service\Alert;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AlertBootstrap
 * @package App\Service\MetaData
 */
class AlertService implements AlertServiceInterface
{
    const ALERT_PRIMARY = "primary";
    const ALERT_SECONDARY = "secondary";
    const ALERT_SUCCESS = "success";
    const ALERT_DANGER = "danger";
    const ALERT_WARNING = "warning";
    const ALERT_INFO = "info";
    const ALERT_LIGHT = "light";
    const ALERT_DARK = "dark";

    /**
     * @var Session
     */
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @inheritDoc
     */
    public function primary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_PRIMARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function secondary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SECONDARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SUCCESS, $message);
    }

    /**
     * @inheritDoc
     */
    public function danger(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DANGER, $message);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_WARNING, $message);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_INFO, $message);
    }

    /**
     * @inheritDoc
     */
    public function light(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_LIGHT, $message);
    }

    /**
     * @inheritDoc
     */
    public function dark(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DARK, $message);
    }
}
