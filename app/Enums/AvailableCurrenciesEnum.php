<?php


namespace App\Enums;


enum AvailableCurrenciesEnum : string {
    case USD = 'USD';
    case ETH = 'Ethereum';
    case POL = 'Polygon';
    case ARB = 'Arbitrum';

    public function getCoinGeckoId(): string
    {
        return match ($this){
            self::ETH => 'ethereum',
            self::POL => 'matic-network',
            self::ARB => 'arbitrum',
            self::USD => 'usd',
        };
    }

    public function getContractAddress(): string
    {
        return match ($this){
            self::ETH => env('ETHEREUM_CONTRACT_ADDRESS'),
            self::POL => env('POLYGON_CONTRACT_ADDRESS'),
            self::ARB => env('ARBITRUM_CONTRACT_ADDRESS'),
            self::USD => '',
        };
    }

    public function getBlockExplorerData(): array
    {
        return match ($this){
            self::ETH => [
                'apiKey' => env('ETHERSCAN_API_KEY'),
                'explorerLink' => 'https://api-sepolia.etherscan.io',
            ],
            self::POL => [
                'apiKey' => env('POLYGONSCAN_API_KEY'),
                'explorerLink' => 'https://api.polygonscan.com',
            ],
            self::ARB => [
                'apiKey' => env('ARBISCAN_API_KEY'),
                'explorerLink' => 'https://api-sepolia.arbiscan.io',
            ],
            self::USD => [],
        };
    }

}
