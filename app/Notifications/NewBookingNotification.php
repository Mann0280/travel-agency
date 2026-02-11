<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $packageName = $this->booking->package ? $this->booking->package->name : 'Unknown Package';
        $customerName = $this->booking->user ? $this->booking->user->name : 'Guest User';
        $agencyName = $this->booking->package && $this->booking->package->agency ? $this->booking->package->agency->name : 'Unknown Agency';

        return (new MailMessage)
                    ->subject('New Booking Lead: ' . $packageName)
                    ->greeting('Hello Admin,')
                    ->line('A new booking lead has been generated.')
                    ->line('**Package:** ' . $packageName)
                    ->line('**Customer:** ' . $customerName)
                    ->line('**Agency:** ' . $agencyName)
                    ->line('**Source:** ' . ucfirst(str_replace('_', ' ', $this->booking->booking_source)))
                    ->action('View Booking', route('admin.bookings.show', $this->booking->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'New booking lead for ' . ($this->booking->package->name ?? 'Package') . ' by ' . ($this->booking->user->name ?? 'Guest'),
            'link' => route('admin.bookings.show', $this->booking->id),
        ];
    }
}
