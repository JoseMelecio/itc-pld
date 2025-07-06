<?php

namespace Database\Seeders;

use App\Models\EBRRiskZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EBRRiskZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["risk_zone" => "estado de mexico", "incidence_of_crime" => 97417, "percentage_1" => 18.59, "percentage_2" => 3.1, "zone" => 1, "color" => "E2EFDA"],
            ["risk_zone" => "ciudad de mexico", "incidence_of_crime" => 55432, "percentage_1" => 10.58, "percentage_2" => 1.74, "zone" => 2, "color" => "D9E1F2"],
            ["risk_zone" => "guanajuato", "incidence_of_crime" => 37128, "percentage_1" => 7.09, "percentage_2" => 1.18, "zone" => 2, "color" => "D9E1F2"],
            ["risk_zone" => "jalisco", "incidence_of_crime" => 32371, "percentage_1" => 6.18, "percentage_2" => 1.03, "zone" => 2, "color" => "D9E1F2"],
            ["risk_zone" => "baja california norte", "incidence_of_crime" => 29552, "percentage_1" => 5.64, "percentage_2" => 0.94, "zone" => 3, "color" => "FFF2CC"],
            ["risk_zone" => "nuevo leon", "incidence_of_crime" => 22115, "percentage_1" => 4.22, "percentage_2" => 0.7, "zone" => 3, "color" => "FFF2CC"],
            ["risk_zone" => "veracruz", "incidence_of_crime" => 20515, "percentage_1" => 3.92, "percentage_2" => 0.65, "zone" => 3, "color" => "FFF2CC"],
            ["risk_zone" => "puebla", "incidence_of_crime" => 18809, "percentage_1" => 3.59, "percentage_2" => 0.6, "zone" => 3, "color" => "FFF2CC"],
            ["risk_zone" => "chihuahua", "incidence_of_crime" => 18599, "percentage_1" => 3.55, "percentage_2" => 0.59, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "querétaro", "incidence_of_crime" => 14830, "percentage_1" => 2.83, "percentage_2" => 0.47, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "coahuila", "incidence_of_crime" => 14357, "percentage_1" => 2.74, "percentage_2" => 0.46, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "san luis potosi", "incidence_of_crime" => 14214, "percentage_1" => 2.71, "percentage_2" => 0.45, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "hidalgo", "incidence_of_crime" => 13534, "percentage_1" => 2.58, "percentage_2" => 0.43, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "quintana roo", "incidence_of_crime" => 13293, "percentage_1" => 2.54, "percentage_2" => 0.42, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "michoacán", "incidence_of_crime" => 11795, "percentage_1" => 2.25, "percentage_2" => 0.38, "zone" => 4, "color" => "DBDBDB"],
            ["risk_zone" => "morelos", "incidence_of_crime" => 11068, "percentage_1" => 2.11, "percentage_2" => 0.35, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "aguascalientes", "incidence_of_crime" => 10864, "percentage_1" => 2.07, "percentage_2" => 0.35, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "tabasco", "incidence_of_crime" => 10530, "percentage_1" => 2.01, "percentage_2" => 0.35, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "tamaulipas", "incidence_of_crime" => 9774, "percentage_1" => 1.87, "percentage_2" => 0.31, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "oaxaca", "incidence_of_crime" => 9487, "percentage_1" => 1.81, "percentage_2" => 0.3, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "sonora", "incidence_of_crime" => 8189, "percentage_1" => 1.56, "percentage_2" => 0.26, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "sinaloa", "incidence_of_crime" => 7974, "percentage_1" => 1.52, "percentage_2" => 0.25, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "zacatecas", "incidence_of_crime" => 6364, "percentage_1" => 1.21, "percentage_2" => 0.2, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "guerrero", "incidence_of_crime" => 6291, "percentage_1" => 1.2, "percentage_2" => 0.2, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "colima", "incidence_of_crime" => 6106, "percentage_1" => 1.17, "percentage_2" => 0.19, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "baja california sur", "incidence_of_crime" => 5668, "percentage_1" => 1.08, "percentage_2" => 0.18, "zone" => 5, "color" => "C9C9C9"],
            ["risk_zone" => "durango", "incidence_of_crime" => 4753, "percentage_1" => 0.91, "percentage_2" => 0.15, "zone" => 6, "color" => "92D050"],
            ["risk_zone" => "campeche", "incidence_of_crime" => 4678, "percentage_1" => 0.89, "percentage_2" => 0.15, "zone" => 6, "color" => "92D050"],
            ["risk_zone" => "chiapas", "incidence_of_crime" => 3164, "percentage_1" => 0.6, "percentage_2" => 0.1, "zone" => 6, "color" => "92D050"],
            ["risk_zone" => "nayarit", "incidence_of_crime" => 3159, "percentage_1" => 0.6, "percentage_2" => 0.1, "zone" => 6, "color" => "92D050"],
            ["risk_zone" => "yucatán", "incidence_of_crime" => 1051, "percentage_1" => 0.2, "percentage_2" => 0.03, "zone" => 6, "color" => "92D050"],
            ["risk_zone" => "tlaxcala", "incidence_of_crime" => 896, "percentage_1" => 0.17, "percentage_2" => 0.03, "zone" => 6, "color" => "92D050"],
        ];

        EBRRiskZone::truncate();
        EBRRiskZone::insert($data);
    }
}
