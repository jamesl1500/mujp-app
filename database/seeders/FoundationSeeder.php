<?php

namespace Database\Seeders;

use App\Models\Foundation;
use Illuminate\Database\Seeder;

class FoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $foundations = [
            ["name" => "Stichting INGKA Foundation"],
            ["name" => "Bill & Melinda Gates Foundation"],
            ["name" => "Wellcome Trust"],
            ["name" => "Howard Hughes Medical Institute"],
            ["name" => "The MasterCard Foundation"],
            ["name" => "Lilly Endowment"],
            ["name" => "Ford Foundation"],
            ["name" => "Robert Wood Johnson Foundation"],
            ["name" => "J. Paul Getty Trust"],
            ["name" => "William and Flora Hewlett Foundation"],
            ["name" => "The Church Commissioners for England"],
            ["name" => "Kamehameha Schools"],
            ["name" => "Bloomberg Philanthropies"],
            ["name" => "Silicon Valley Community Foundation"],
            ["name" => "W.K. Kellogg Foundation"],
            ["name" => "David and Lucile Packard Foundation"],
            ["name" => "The Pew Charitable Trust"],
            ["name" => "John D. and Catherine T. MacArthur Foundation"],
            ["name" => "Andrew W. Mellon Foundation"],
            ["name" => "Gordon Betty Moore Foundation"],
            ["name" => "The Leona M. and Harry B. Helmsley Charitable Trust"],
            ["name" => "Tulsa Community Foundation"],
            ["name" => "Realdania"],
            ["name" => "Rockefeller Foundation"],
            ["name" => "Walton Family Foundation Inc"],
            ["name" => "The California Endowment"],
            ["name" => "Robert W. Woodruff Foundation Inc."],
            ["name" => "The Kresge Foundation"],
            ["name" => "The Duke Endowment"],
            ["name" => "Carnegie Corporation of New York"],
            ["name" => "The JPB Foundation"],
            ["name" => "The Simons Foundation"],
            ["name" => "The Chicago Community Trust"],
            ["name" => "Greater Kansas City Community Foundation"],
            ["name" => "Charles Stewart Mott Foundation"],
            ["name" => "Margaret A. Cargill Foundation"],
            ["name" => "The Moody Foundation"],
            ["name" => "Kimbell Art Foundation"],
            ["name" => "Conrad N. Hilton Foundation"],
            ["name" => "Calouste Gulbenkian Foundation"],
            ["name" => "The Annie E. Casey Foundatio"],
            ["name" => "The Susan Thompson Buffett Foundation"],
            ["name" => "The New York Community Trust"],
            ["name" => "William Penn Foundation"],
            ["name" => "Richard King Mellon Foundation"],
            ["name" => "Ewing Marion Kauffman Foundation"],
            ["name" => "The Wyss Foundation"],
            ["name" => "Foundation For The Carolinas"],
            ["name" => "Charles and Lynn Schusterman Family Foundation"],
            ["name" => "The Oregon Community Foundation"],
            ["name" => "The James Irvine Foundation"],
            ["name" => "John S. and James L. Knight Foundation"],
            ["name" => "Laura and John Arnold Foundation"],
            ["name" => "The McKnight Foundation"],
            ["name" => "The Columbus Foundation and Affiliated Organizations"],
            ["name" => "Doris Duke Charitable Foundation"],
            ["name" => "Eli & Edythe Broad Foundation"],
            ["name" => "California Community Foundation"],
            ["name" => "Barr Foundation"],
            ["name" => "Alfred P. Sloan Foundation"],
            ["name" => "The Heinz Endowments"],
            ["name" => "The Michael and Susan Dell Foundation"],
            ["name" => "Annenberg Foundation"],
            ["name" => "The San Francisco Foundation"],
            ["name" => "The Starr Foundation"],
            ["name" => "John Templeton Foundation"],
            ["name" => "The Wallace Foundation"],
            ["name" => "The Brown Foundation Inc."],
            ["name" => "Houston Endowment Inc."],
            ["name" => "Boston Foundation Inc."],
            ["name" => "Shimon ben Joseph Foundation"],
            ["name" => "Druckenmiller Foundation"],
            ["name" => "The Ahmanson Foundation"],
            ["name" => "M. J. Murdock Charitable Trust"],
            ["name" => "Bat Hanadiv Foundation No. 3"],
            ["name" => "Lumina Foundation"],
            ["name" => "The J. E. and L. E. Mabee Foundation Inc."],
            ["name" => "W. M. Keck Foundation"],
            ["name" => "The Pittsburgh Foundation"],
            ["name" => "Rockefeller Brothers Fund Inc."],
            ["name" => "Communities Foundation of Texas Inc."],
            ["name" => "Foundation to Promote Open Society"],
            ["name" => "The Samuel Roberts Noble Foundation Inc."],
            ["name" => "Otto Bremer Foundation"],
            ["name" => "Surdna Foundation Inc."],
            ["name" => "George Lucas Family Foundation"],
            ["name" => "The Joyce Foundation"],
            ["name" => "The Wolfson Foundation"],
            ["name" => "Hartford Foundation for Public Giving"],
            ["name" => "The Edna McConnell Clark Foundation"],
            ["name" => "Hall Family Foundation"],
            ["name" => "The California Wellness Foundation"],
            ["name" => "The Henry Luce Foundation Inc."],
            ["name" => "The Anschutz Foundation"],
            ["name" => "Marin Community Foundation"],
            ["name" => "Cummings Foundation Inc."]
        ];

        foreach ($foundations as $foundation){
            Foundation::create($foundation);
        }
    }
}
