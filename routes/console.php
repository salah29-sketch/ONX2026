<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:cancel-expired-pending')->daily();
Schedule::command('subscriptions:process-renewals')->daily();
