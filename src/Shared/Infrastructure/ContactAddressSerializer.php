<?php

declare(strict_types=1);

namespace Xtompie\Aggidea\Shared\Infrastructure;

use Xtompie\Aggidea\Shared\Domain\ContactAddress;

class ContactAddressSerializer
{
    public function model(array $primitive): ContactAddress
    {
        return new ContactAddress(
            $primitive['first_name'],
            $primitive['last_name'],
            $primitive['telephone'],
            $primitive['locality'],
            $primitive['postal_code'],
            $primitive['street_name'],
            $primitive['street_number'],
        );
    }

    public function primitive(ContactAddress $contactAddress): array
    {
        return [
            $contactAddress->firstName(),
            $contactAddress->lastName(),
            $contactAddress->firstName(),
            $contactAddress->telephone(),
            $contactAddress->locality(),
            $contactAddress->postalCode(),
            $contactAddress->streetName(),
            $contactAddress->streetNumber(),
        ];
    }
}
