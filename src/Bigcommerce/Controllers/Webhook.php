<?php

namespace Oseintow\Bigcommerce\Controllers;

use Illuminate\Support\Facades\Log;

class Webhook
{
    public function process()
    {
        if (request()->header('X-Bcl-Secret') !== config('bigcommerce.webhook_secret')) {
            return response('Invalid secret', 406);
        }

        $msg = sprintf('Got webhook *%s* with data ```%s```',
            request('scope'),
            print_r(request('data'), TRUE));

        Log::stack(['slack'])->info($msg);

        // Return a 200.
        return response('OK');
    }
}
