<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accommodation;

class AccommodationsTableSeeder extends Seeder
{
    public function run()
    {
        // Primero limpiamos la tabla
        Accommodation::truncate();

        // PONT DE MOLINS
        Accommodation::create([
            'name' => 'Hotel El Molí de les Escaules',
            'zone' => 'PONT DE MOLINS',
            'stars' => '***',
            'distance' => 3.3,
            'website' => 'www.hotelelmoli.es',
            'discount_info' => 'En este hotel, el descuento no es monetario. Registrando el código "FARINERA", podréis beneficiaros de un "earlier check-in" y "later checkout". Se requiere una reserva mínima de 2 noches.',
            'order' => 1
        ]);

        // FIGUERES
        Accommodation::create([
            'name' => 'Hotel Figueres Parc',
            'zone' => 'FIGUERES',
            'stars' => '**',
            'distance' => 3.2,
            'website' => 'www.hotelfigueresparc.com',
            'discount_info' => 'Este alojamiento ofrece un pequeño descuento si se hace la reserva a través de la web.',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Hotel Empordà',
            'zone' => 'FIGUERES',
            'stars' => '****',
            'distance' => 5.1,
            'website' => 'www.hotelemporda.com',
            'discount_info' => 'Este alojamiento ofrece un descuento en la web con el código "BODAMH". La reserva debe hacerse en la web del hotel.',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Hotel Duran',
            'zone' => 'FIGUERES',
            'stars' => '****',
            'distance' => 6.3,
            'website' => 'www.hotelduran.com',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Sercotel President',
            'zone' => 'FIGUERES',
            'stars' => '****',
            'distance' => 6.3,
            'website' => 'www.sercotelhoteles.com',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Hotel Rambla',
            'zone' => 'FIGUERES',
            'stars' => '***',
            'distance' => 6.5,
            'website' => 'www.hotelrambla.net',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Rambla32 Boutique Apartments',
            'zone' => 'FIGUERES',
            'stars' => null,
            'distance' => 6.5,
            'website' => 'https://www.booking.com/hotel/es/r32-boutique-apartments.es.html',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Center Plaza Figueres',
            'zone' => 'FIGUERES',
            'stars' => null,
            'distance' => 6.7,
            'website' => 'www.centerplazafigueres.com',
            'discount_info' => 'Este establecimiento puede tener algún tipo de descuento o beneficio "earlier check-in" o "later check-out" si les decís que sois invitados de una boda que se celebrará en La Farinera Sant Lluis. No obstante, no nos han contestado (confirmad con ellos si os interesa el alojamiento).',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Hotel Pirineos',
            'zone' => 'FIGUERES',
            'stars' => '****',
            'distance' => 6.9,
            'website' => 'www.pirineoshotelfigueres.com',
            'discount_info' => 'Este alojamiento ofrece un descuento en la web con el código "FARINERASLL". La reserva debe hacerse en la web del hotel.',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Ibis Styles Figueres Ronda',
            'zone' => 'FIGUERES',
            'stars' => '***',
            'distance' => 7.2,
            'website' => 'www.hotelrondafigueres.com',
            'order' => 2
        ]);

        Accommodation::create([
            'name' => 'Figueres Apartaments',
            'zone' => 'FIGUERES',
            'stars' => null,
            'distance' => 8.1,
            'website' => 'www.figueresapartaments.com',
            'order' => 2
        ]);

        // PERELADA
        Accommodation::create([
            'name' => 'Hotel Golf Wine & Spa Peralada',
            'zone' => 'PERELADA',
            'stars' => '*****',
            'distance' => 10.0,
            'website' => 'www.hotelperalada.com',
            'order' => 3
        ]);

        // PAU I ROSES
        Accommodation::create([
            'name' => 'Hotel Mas Lazuli',
            'zone' => 'PAU I ROSES',
            'stars' => '****',
            'distance' => 21.0,
            'website' => 'www.hotelmaslazuli.es',
            'discount_info' => 'Este alojamiento no ofrece descuentos por bodas.',
            'order' => 4
        ]);

        Accommodation::create([
            'name' => 'Hotel Terraza',
            'zone' => 'PAU I ROSES',
            'stars' => '****',
            'distance' => 24.0,
            'website' => 'www.hotelterraza.com',
            'discount_info' => 'Este alojamiento únicamente genera un código de descuento si se reservan un mínimo de habitaciones.',
            'order' => 4
        ]);

        Accommodation::create([
            'name' => 'Hotel 1935',
            'zone' => 'PAU I ROSES',
            'stars' => '****',
            'distance' => 24.0,
            'website' => 'www.hotel1935.com',
            'discount_info' => '(hotel y apartamentos)',
            'order' => 4
        ]);

        // ALOJAMIENTOS DEL GRUPO FARINERA
        Accommodation::create([
            'name' => 'Mas el Brugué',
            'zone' => 'ALOJAMIENTOS DEL GRUPO FARINERA',
            'stars' => null,
            'distance' => 13.0,
            'website' => 'www.maselbrugue.com',
            'is_farinera_group' => true,
            'order' => 5
        ]);

        Accommodation::create([
            'name' => 'Villa la costa',
            'zone' => 'ALOJAMIENTOS DEL GRUPO FARINERA',
            'stars' => null,
            'distance' => 26.0,
            'website' => 'www.lescasetes.com/les-finques/villa-la-costa/',
            'is_farinera_group' => true,
            'order' => 5
        ]);
    }
}
