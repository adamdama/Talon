<?php
namespace Talon\Mail;

use \Phalcon\Mvc\User\Component,
	\Phalcon\Mvc\View,
	Swift_Message,
	Swift_Mailer,
	Swift_SmtpTransport,
	Swift_SendmailTransport;

class Mail extends Component {

	/** @var  Swift_Message */
	protected $message;

	protected $senders = array();

	protected $recipients = array();

	protected $transport;

	protected $mailer;

	public function addRecipient($recipient) {
		$this->addEmail($recipient, $this->recipients);
	}

	public function removeRecipient($email) {
		$this->removeEmail($email, $this->recipients);
	}

	public function addSender($sender) {
		$this->addEmail($sender, $this->senders);
	}

	public function removeSender($email) {
		$this->removeEmail($email, $this->senders);
	}

	protected function addEmail($email, array &$list) {
		if(is_object($email))
			$email = (array) $email;

		if(is_array($email)) {
			$filtered = array();

			foreach($email as $key => $r) {
				if(is_numeric($key)&& !in_array($r, $list)) {
					$filtered[] = $r;
				} elseif(!isset($list[$key])) {
					$filtered[$key] = $r;
				}
			}

			$list = array_merge($list, $filtered);
		} elseif(is_string($email) && !isset($list[$email]) && !in_array($email, $list)) {
			$list[] = $email;
		}
	}

	protected function removeEmail($email, array &$list) {
		if(isset($list[$email])) {
			unset($list[$email]);
		} else {
			$index = array_search($email, $email);
			array_splice($list, $index, 1);
		}
	}

	protected function getTemplate($name, array $params) {
		$parameters = array_merge(array(
			'publicUrl' => $this->config->application->publicUrl
		), $params);

		return $this->view->getRender('emails', $name, $parameters, function (View $view) {
			$view->setRenderLevel(View::LEVEL_LAYOUT);
		});
	}

	public function send($subject, $templateName, array $templateParams, $to = null, $from = null) {
		if($to !== null)
			$this->addRecipient($to);

		if($from !== null)
			$this->addSender($from);

		// Settings
		if(isset($this->config->mail)) {
			$mailSettings = $this->config->mail;
		} else {
			throw new MailException(MailException::NO_CONFIG_SET);
		}

		if(empty($this->senders)) {
			$this->addSender($this->config->mail->senders->default);
		}

		// Create the message
		$this->message = Swift_Message::newInstance(null);

		$this->message->setTo($this->recipients);
		$this->message->setFrom($this->senders);

		// set subject
		$this->message->setSubject($subject);

		// email content
		$template = $this->getTemplate($templateName, $templateParams);
		$this->message->setBody($template, 'text/html');

		// create a transport
		if (!$this->transport) {
			if (isset($mailSettings->smtp)) {
				$this->transport = Swift_SmtpTransport::newInstance(
					$mailSettings->smtp->server,
					$mailSettings->smtp->port,
					$mailSettings->smtp->security
				);

				$this->transport->setUsername($mailSettings->smtp->username);
				$this->transport->setPassword($mailSettings->smtp->password);
			} else {
				$this->transport = Swift_SendmailTransport::newInstance();
			}

			// Create the Mailer using your created Transport
			$this->mailer = Swift_Mailer::newInstance($this->transport);
		}

		if (!$this->mailer->send($this->message, $failures)) {
			throw new MailException(print_r($failures, true));
		}

		return true;
	}
} 