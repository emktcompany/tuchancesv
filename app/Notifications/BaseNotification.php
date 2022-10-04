<?php

namespace App\Notifications;

use App\TuChance\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

abstract class BaseNotification extends Notification
{
    use Queueable, SerializesModels;

    /**
     * The folder the messages for this notifications are stored
     * @var string
     */
    protected $translations;

    /**
     * Url for action button
     * @param  mixed $notifiable
     * @return string
     */
    public function url($notifiable)
    {
        return url('/');
    }

    /**
     * Get the notification's delivery channels.
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Replace data for translations
     * @return array
     */
    public function replace($notifiable)
    {
        return [];
    }

    /**
     * Translate line
     * @param  string $line
     * @return string|null
     */
    public function getLine($line, $notifiable)
    {
        $key  = "notifications/{$this->translations}.{$line}";
        $line = __($key, $this->replace($notifiable));
        return $line == $key ? null : $line;
    }

    /**
     * Get the mail representation of the notification.
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage;

        /** @var EmailTemplate $template */
        $template = (new EmailTemplate)
            ->where('event', $this->translations)
            ->first();

        $this->composeFromDatabase($message, $notifiable, $template);

        $message->view('emails.notification', compact('notifiable', 'template'));

        return $message;
    }

    /**
     * @param EmailTemplate $template
     * @param MailMessage $message
     */
    protected function composeFromDatabase(MailMessage $message, $notifiable, EmailTemplate $template = null)
    {
        if ($template && $template->title) {
            $message->subject($template->title);
            $message->greeting($template->title);
        } else {
            if ($line = $this->getLine('name', $notifiable)) {
                $message->subject($line);
            }

            if ($line = $this->getLine('head', $notifiable)) {
                $message->greeting($line);
            }
        }

        if ($template && !empty($template->content)) {
            foreach ($template->content as $line) {
                $message->line($this->makeReplacements($line, $this->replace($notifiable)));
            }
        } else {
            if ($line = $this->getLine('text', $notifiable)) {
                $message->line($line);
            }

            if ($line = $this->getLine('data', $notifiable)) {
                $message->line($line);
            }
        }

        if (method_exists($this, 'toMailData')) {
            $this->toMailData($message, $notifiable);
        }

        if ($template && $template->cta && $template->cta_text) {
            $message->action($template->cta_text, $template->cta);
        } else {
            if (
                ($action = $this->getLine('action', $notifiable)) &&
                $url = $this->url($notifiable)
            ) {
                $message->action($action, $url);
            }
        }

        if ($line = $this->getLine('outro', $notifiable)) {
            $message->line($line);
        }
    }

    protected function makeReplacements($line, array $replacements)
    {
        foreach ($replacements as $key => $value) {
            $line = str_replace(
                [':' . $key, ':' . Str::upper($key), ':' . Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
    }

    /**
     * @param MailMessage $message
     */
    protected function composeFromTranslations(MailMessage $message)
    {
        if ($line = $this->getLine('name', $notifiable)) {
            $message->subject($line);
        }

        if ($line = $this->getLine('head', $notifiable)) {
            $message->greeting($line);
        }

        if ($line = $this->getLine('text', $notifiable)) {
            $message->line($line);
        }

        if ($line = $this->getLine('data', $notifiable)) {
            $message->line($line);
        }

        if (method_exists($this, 'toMailData')) {
            $this->toMailData($message, $notifiable);
        }

        if (
            ($action = $this->getLine('action', $notifiable)) &&
            $url = $this->url($notifiable)
        ) {
            $message->action($action, $url);
        }

        if ($line = $this->getLine('outro', $notifiable)) {
            $message->line($line);
        }
    }
}
