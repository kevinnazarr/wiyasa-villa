<?php

return [
    'default_hold_minutes' => max(1, (int) env('BOOKING_HOLD_MINUTES', 15)),
];
