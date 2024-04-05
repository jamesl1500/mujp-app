<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            ["name" => Industry::emptyRecordName()],
            ["name" => "Furniture industry"],
            ["name" => "Industrial coating"],
            ["name" => "Software industry"],
            ["name" => "Steel industry"],
            ["name" => "Externality"],
            ["name" => "Hospitality industry"],
            ["name" => "Industrial design"],
            ["name" => "Industrialist"],
            ["name" => "Cultural industry"],
            ["name" => "Industrial district"],
            ["name" => "Labour revolt"],
            ["name" => "Telecommunications industry"],
            ["name" => "Industrialization"],
            ["name" => "Tobacco industry"],
            ["name" => "Industrial PC"],
            ["name" => "Water industry"],
            ["name" => "Mass media"],
            ["name" => "Sport industry"],
            ["name" => "Education industry"],
            ["name" => "Information industry"],
            ["name" => "Industrial processes"],
            ["name" => "Pricing"],
            ["name" => "Meat"],
            ["name" => "Shipbuilding industry"],
            ["name" => "Industrial applicability"],
            ["name" => "Industrial action"],
            ["name" => "Real estate industry"],
            ["name" => "Food industry"],
            ["name" => "Horticulture industry"],
            ["name" => "Industrial deconcentration"],
            ["name" => "Industry analyst"],
            ["name" => "Science park"],
            ["name" => "Industrial sociology"],
            ["name" => "Industrial unionism"],
            ["name" => "Industrial Revolution"],
            ["name" => "Machining"],
            ["name" => "Standard Industrial Classification"],
            ["name" => "Entertainment industry"],
            ["name" => "Industrial waste"],
            ["name" => "Industrial disasters"],
            ["name" => "Pulp and paper industry"],
            ["name" => "Industrial gas"],
            ["name" => "Air pollution"],
            ["name" => "Chemical industry"],
            ["name" => "Industrial society"],
            ["name" => "Professional services"],
            ["name" => "Low technology industry"],
            ["name" => "Culture industry"],
            ["name" => "Electric power industry"],
            ["name" => "Occupational noise"],
            ["name" => "Market research"],
            ["name" => "Industrial mineral"],
            ["name" => "Healthcare industry"],
            ["name" => "Trade association"],
            ["name" => "Industrial robot industry"],
            ["name" => "Industrial engineering"],
            ["name" => "Automotive industry"],
            ["name" => "Big business"],
            ["name" => "Transport industry"],
            ["name" => "Textile industry"],
            ["name" => "Financial services industry"],
            ["name" => "Machine tooling"],
            ["name" => "Industrial Age"],
            ["name" => "Industrial data processing"],
            ["name" => "Industrial internet of things"],
            ["name" => "Colin Clark"],
            ["name" => "Mass production"],
            ["name" => "Leisure industry"],
            ["name" => "Industrial ecology"],
            ["name" => "Employment tribunal"],
            ["name" => "Occupational injury"],
            ["name" => "Defense industry"],
            ["name" => "Industrial and organizational psychology"],
            ["name" => "Industrial railway"],
            ["name" => "Industrial park"],
            ["name" => "Industrial democracy"],
            ["name" => "Industrial control system"],
            ["name" => "Construction industry"],
            ["name" => "Robber baron (industrialist)"],
            ["name" => "Industrial archaeology"],
            ["name" => "Industry Structure Model"],
            ["name" => "Industrial and production engineering"],
            ["name" => "Materials science"],
            ["name" => "Industrial policy"],
            ["name" => "Fishing industry"],
            ["name" => "Industrial production index"],
            ["name" => "Energy industry"],
            ["name" => "Seven Wonders of the Industrial World"],
            ["name" => "Economies of scale"],
            ["name" => "Creative"],
            ["name" => "Aerospace industry"],
            ["name" => "Petroleum industry"],
            ["name" => "Mining"],
            ["name" => "Wood industry"],
            ["name" => "Industrial history"],
            ["name" => "Industrial design right"],
            ["name" => "Industrial organization"],
            ["name" => "Electronics industry"],
            ["name" => "Raw material"],
            ["name" => "Industrial espionage"],
            ["name" => "Global Industry Classification Standard"]
        ];

        foreach ($industries as $industry){
            Industry::create($industry);
        }
    }
}
