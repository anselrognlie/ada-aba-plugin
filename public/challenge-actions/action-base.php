<?php

namespace Ada_Aba\Public\Challenge_Actions;

use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Challenge_Action;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;
use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Action\Emails;
use Ada_Aba\Public\Action\Links;
use Ada_Aba\Public\Action\Errors;

use function Ada_Aba\Public\Action\Links\redirect_to_error_page;

abstract class Action_Base
{
  private $slug;
  private $email;
  private $nonce;
  private $expires_at;

  protected function __construct(
    $slug,
    $email,
    $nonce,
    $expires_at,
  ) {
    $this->slug = $slug;
    $this->email = $email;
    $this->nonce = $nonce;
    $this->expires_at = $expires_at;
  }

  abstract protected function complete_specific();
  abstract protected function expired();
  abstract protected function to_payload();
  abstract protected function get_builder();
  abstract public function get_email_content();

  public function getSlug()
  {
    return $this->slug;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getNonce()
  {
    return $this->nonce;
  }

  public function getExpiresAt()
  {
    return $this->expires_at;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function complete()
  {
      Transaction::start();
      try {
        $this->complete_specific();
        $this->cleanup();
      } catch (Aba_Exception $e) {
        Transaction::rollback();
        Core::log($e);
        redirect_to_error_page(Errors\COMPLETE_ACTION);
      }
      Transaction::complete();
  }

  public static function get_by_slug($slug)
  {
    self::clean_expired_challenges();

    $challenge = Challenge_Action::get_by_slug($slug);
    if (!$challenge) {
      return null;
    }

    return self::create_from_challenge($challenge);
  }

  public static function get_by_nonce($nonce)
  {
    self::clean_expired_challenges();

    $challenge = Challenge_Action::get_by_nonce($nonce);
    if (!$challenge) {
      return null;
    }

    return self::create_from_challenge($challenge);
  }

  private static function create_from_challenge($challenge)
  {
    $slug = $challenge->getSlug();
    $class = $challenge->getActionBuilder();
    $email = $challenge->getEmail();
    $nonce = $challenge->getNonce();
    $expires_at = $challenge->getExpiresAt();

    $builder = new $class;
    $action = $builder->build(
      $slug,
      $email,
      $nonce,
      $expires_at,
      $challenge->getActionPayload(),
    );

    return $action;
  }

  private static function clean_expired_challenges()
  {
    $challenges = Challenge_Action::get_expired_challenges();
    foreach ($challenges as $challenge) {
      $action = self::create_from_challenge($challenge);
      $action->expired();
      $challenge->delete();
    }
  }

  public function run()
  {
    try
    {
      $this->log_action();
    } catch (Aba_Exception $e) {
      Core::log($e);
      redirect_to_error_page(Errors\LOG_ACTION);
      return;
    }

    $this->notify();
  }

  private function log_action()
  {
    $challenge = Challenge_Action::create(
      $this->email,
      get_class($this->get_builder()),
      $this->to_payload(),
    );

    $challenge->insert();

    $this->slug = $challenge->getSlug();
    $this->nonce = $challenge->getNonce();
    $this->expires_at = $challenge->getExpiresAt();
  }

  public function notify()
  {
    $options = Options::get_options();
    if (!$options->get_send_email()) {
      return;
    }

    $this->send_email();
  }

  private function send_email()
  {
    $to = $this->email;

    [$subject, $body] = $this->get_email_content();
    // $subject = $this->subject;
    // $body = $this->body;

    $verify_link = Links\get_verify_link($this->nonce);
    $footer = $this->get_email_footer($verify_link);

    Emails::mail($to, $subject, $body . $footer);
  }

  private function cleanup()
  {
    Core::log(sprintf('%1$s::%2$s: slug: %3$s', __CLASS__, __FUNCTION__, $this->slug));
    $challenge = Challenge_Action::get_by_slug($this->slug);
    $challenge->delete();
  }

  private function get_email_footer($verify_link)
  {
    ob_start();
    include __DIR__ . '/../partials/action-footer-email.php';
    return ob_get_clean();
  }
}
