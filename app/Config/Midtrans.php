namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey;
    public string $clientKey;

    public bool $isProduction;
    public bool $isSanitized;
    public bool $is3ds;

    public function __construct()
    {
        $this->serverKey = env('midtrans.serverKey');
        $this->clientKey = env('midtrans.clientKey');

        $this->isProduction = env('midtrans.production', false);
        $this->isSanitized = true;
        $this->is3ds = true;
    }
}