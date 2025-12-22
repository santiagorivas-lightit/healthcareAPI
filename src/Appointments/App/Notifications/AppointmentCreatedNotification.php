<?php

declare(strict_types=1);

namespace Lightit\Appointments\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lightit\Appointments\Domain\Models\Appointment;

class AppointmentCreatedNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(Appointment $notifiable): MailMessage
    {
        return new MailMessage()
            ->markdown('mail.appointment-created', [
                'appointmentNumber' => $notifiable->id,
                'startingTime' => $notifiable->starts_at,
                'clinic' => $notifiable->clinic,
                'doctor' => $notifiable->doctor,
            ]);
    }
}
