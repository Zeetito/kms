<?php

namespace Database\Seeders;

use App\Models\Residence;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $residences = [
            // BOADI ZONE 1
            ['name' => 'Urban platinum. ', 'landmark' => 'none', 'description' => 'Behind Mount Oliver prayer Camp (Boadi station) ', 'zone_id' => 1],
            ['name' => 'Boadi Executive', 'landmark' => 'none', 'description' => 'Behind Mount Oliver prayer camp (Boadi station) ', 'zone_id' => 1],
            ['name' => 'LA Casa Hostel', 'landmark' => 'none', 'description' => 'African Child school', 'zone_id' => 1],
            ['name' => 'K GEE Hostel', 'landmark' => 'none', 'description' => 'Boadi Mongo Ase junction', 'zone_id' => 1],
            // ['name' => 'No name ', 'landmark' => 'none', 'description' => '5 meters before you reach K GEE Hostel ', 'zone_id' => 1],
            // ['name' => 'No name', 'landmark' => 'none', 'description' => 'close to hostel 4', 'zone_id' => 1],
            ['name' => 'K 5 hostel', 'landmark' => 'none', 'description' => 'K5 junction', 'zone_id' => 1],
            ['name' => '4 Seasons hostel', 'landmark' => 'none', 'description' => 'none',  'zone_id' => 1],
            ['name' => 'D. K Appiah Royal Hostel', 'landmark' => 'none', 'description' => 'Behind Urban Platinum', 'zone_id' => 1],

            // BOMSO 2
            ['name' => 'Adwoa Akyaa', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Evandy ultimate hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Ghana Bible College', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Homestel behind  c.o.c Bomso', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'IPS', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Joshua Hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'KNUSTFORD hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Royal Gate', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Samens hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Standard hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],
            ['name' => 'Ultimate Hostel', 'landmark' => 'none', 'description' => 'none', 'zone_id' => 2],

            // BRUNEI 3
            ['name' => 'Baby Brunei', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Brunei Complex', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'New Brunei', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Old Brunei', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Spring Hostel', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Steven Paris Hostel', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Tek Credit', 'zone_id' => 3, 'description' => 'none', 'landmark' => 'none'],

            // FORD,NYBERG AND SPLENDOR 4
            ['name' => 'Akoto Hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Delisa', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Dr Sarfo hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Emmalville', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Eunivic Hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'FORD HOSTEL', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Frontline premium tower', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Homestel adjacent Peace hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Mountain View', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'New Evandy', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nyberg', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Peace hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Shepherdsville hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Splendor Hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Stone castle hostel', 'zone_id' => 4, 'description' => 'none', 'landmark' => 'none'],

            // GAZA DEDUAKO KENTINKRONO 5
            ['name' => 'Crystal rose', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Wilkado', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Anglican', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Sun city', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Viable ', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Gaza', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Banivillas', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Georgia ', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Sir max', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Ashmore', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Destiny view', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'Kent City', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'zero one.', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],
            ['name' => 'kasados', 'landmark' => 'none', 'zone_id' => 5, 'description' => 'none'],

            // KOTEI ZONE 6
            ['name' => 'Rising sun', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Canam', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hopes community', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Liberty', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Brims down', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Mass', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Bannant manor', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Canam Premium', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Canam hall', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Frontline court', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'PTP', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Providence', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Jecado', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Anarosa', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Besco', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Devalypa', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Thywill', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Johannes', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Canadian', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Richco', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'New Franco', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hallowed', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hillton', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Classic homstel', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hostels behind', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => "Sallah's homstel", 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hostel around Gazelle community school ', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Simon Sekyere', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Algrace', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Orange', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Celia Royal', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => "St Peter's", 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Amstel', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'J. K P hostel', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Machester', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Hydes', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Amen', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Platinum', 'zone_id' => 6, 'description' => 'none', 'landmark' => 'none'],

            //  NEWSITE 7
            ['name' => 'Afrane hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Ayeduase New site Homestel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'B- Mark', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Caesar’s palace', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Crystal Palace', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Evandy Hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'F&F hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Fortune Hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Fosuaa homes', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Glory be to God', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Jecado hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Moonlight Hotel, new site', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Morning Star Palace Hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'MR MOSES HOMESTEL', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nabellena', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nad hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nana homestel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nevada hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'NS hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Nyantakyi hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Outlook Hostel', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Resurrection', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Shepherds Ville', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Wisdom seekers', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Yahoda', 'zone_id' => 7, 'description' => 'none', 'landmark' => 'none'],

            // SHALOM ZONE 8
            ['name' => 'Afirim hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Ibiza foods', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => " Homestel beside sweet mummy's", 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Nana Adomah Hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Nana Adomah hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Obroni Naa hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Whitehouse hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Amanda hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Amanda hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Morning star palace hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'R n B hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Lion of Judah hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Orazin hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Orazin hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Canam gold hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Stonecaste hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Rising sun hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite rising sun', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Jalex hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Ebenezer hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'No weapon hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'No weapon annex hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => "St Theresah's hostel", 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Frontline inn', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Shalom hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite No weapon hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Franco hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Paradise regained hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Casa Maria hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Casa Maria hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Hostel opposite Franco hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => "St Peter's villa", 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => "Hyde's hostel", 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Amen apartment', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Fredmark hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Peniel hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel opposite Peniel hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Amen hostel main', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'ABC hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Frontline apartment', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Wagyingo Onyx hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Nana Abena hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Nyame Mireku hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Yvonna hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Adom bi hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Amen inn hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Amen annex hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Happy family hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Homestel beside P3 hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Jesus height hostel', 'zone_id' => 8,  'landmark' => 'none', 'description' => 'none'],

            // WESTEND ZONE 9
            ['name' => 'By His grace', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Golden paradise ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'West end ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Beacon ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'B O Executive ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Victory towers', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Dr. Safo ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Evandy annex', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Ankana hostel', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Thy Kingdom come', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Glory be to God.', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Yaw Atta hostel', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Continental ', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],
            ['name' => 'Eden', 'zone_id' => 9, 'landmark' => 'none', 'description' => 'none'],

            // AFRICA ZONE 10
            ['name' => 'Africa Hall', 'zone_id' => 10, 'description' => 'none', 'landmark' => 'none'],

            // REPUBLIC HALL ZONE 11
            ['name' => 'Republic Hall (Repu)', 'zone_id' => 11, 'description' => 'none', 'landmark' => 'none'],

            // INDEPENDENCE HALL ZONE 12
            ['name' => 'Independence Hall (Indece)', 'zone_id' => 12, 'description' => 'none', 'landmark' => 'none'],

            // QUEEN ELIZABETH HALL ZONE 13
            ['name' => 'Queen Elizabeth II (Queens Hall)', 'zone_id' => 13, 'description' => 'none', 'landmark' => 'none'],

            // UNITY HALL ZONE 14
            ['name' => 'Unity Hall (Conti)', 'zone_id' => 14, 'description' => 'none', 'landmark' => 'none'],

            // UNIVERSITY HALL  ZONE 15
            ['name' => 'University Hall (Katanga)', 'zone_id' => 15, 'description' => 'none', 'landmark' => 'none'],

            // SRC,IMPACT, HALL 7 ZONE 16
            ['name' => "Chancellor's Hall (Hall 7)", 'zone_id' => 16, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'Impact Building', 'zone_id' => 16, 'description' => 'none', 'landmark' => 'none'],
            ['name' => 'SRC Hostel', 'zone_id' => 16, 'description' => 'none', 'landmark' => 'none'],

        ];

        foreach ($residences as $residence) {
            Residence::create($residence);
        }

        // Residence::factory(250)->create();
    }
}
