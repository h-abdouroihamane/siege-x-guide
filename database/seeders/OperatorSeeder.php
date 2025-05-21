<?php
namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Ace',
                'description' =>
                    'When thrown onto a breachable surface, the SELMA will extend a set of explosive arms, then detonate, creating a wide opening',
                'side' => 'Attack',
                'year' => 5,
                'season' => 2,
            ],
            [
                'name' => 'Alibi',
                'description' =>
                    'Deploys three holograms which reveal the location and the identity of the attacker who shoots through it',
                'side' => 'Defense',
                'year' => 3,
                'season' => 2,
            ],
            [
                'name' => 'Amaru',
                'description' =>
                    'Can quickly propel herself through windows/hatches and on top of buildings with her grappling hook',
                'side' => 'Attack',
                'year' => 4,
                'season' => 3,
            ],
            [
                'name' => 'Aruni',
                'description' =>
                    'Can place laser gates that deal damage to anyone passing through. They can be disabled for 30s by throwing any projectile through them',
                'side' => 'Defense',
                'year' => 5,
                'season' => 4,
            ],
            [
                'name' => 'Ash',
                'description' =>
                    'Her gadget can shoot up to 2 Breaching Rounds, which will burrow into a surface and detonate automatically',
                'side' => 'Attack',
                'year' => 0,
                'season' => 3,
            ],
            [
                'name' => 'Azami',
                'description' =>
                    'Has up to 5 Kiba Barriers - modified kunais that sticks to a surface after being thrown and release a material that creates a bulletproof barrier',
                'side' => 'Defense',
                'year' => 7,
                'season' => 1,
            ],
            [
                'name' => 'Bandit',
                'description' =>
                    'Can put down batteries which electrifies walls or barb wire - destroying any electrified gadget',
                'side' => 'Defense',
                'year' => 0,
                'season' => 10,
            ],
            [
                'name' => 'Blackbeard',
                'description' =>
                    'Has a shield with a retractable bulletproof glass that slides down so he can shoot while still using his shield',
                'side' => 'Attack',
                'year' => 1,
                'season' => 2,
            ],
            [
                'name' => 'Blitz',
                'description' => 'Flashes people with his shield',
                'side' => 'Attack',
                'year' => 0,
                'season' => 9,
            ],
            [
                'name' => 'Brava',
                'description' =>
                    "Has a drone that can hack defenders' electronic gadgets and depending of the gadget: take control of it or turn it back against defenders or simply destroy it",
                'side' => 'Attack',
                'year' => 8,
                'season' => 1,
            ],
            [
                'name' => 'Buck',
                'description' => 'Has an under-barrel shotgun',
                'side' => 'Attack',
                'year' => 1,
                'season' => 1,
            ],
            [
                'name' => 'Capitão',
                'description' =>
                    'Has a crossbow which can shoot two asphyxiating bolts and two micro-smoke grenades',
                'side' => 'Attack',
                'year' => 1,
                'season' => 3,
            ],
            [
                'name' => 'Castle',
                'description' =>
                    'Can put up 4 reinforced barricades which can only be taken down with explosive devices',
                'side' => 'Defense',
                'year' => 0,
                'season' => 3,
            ],
            [
                'name' => 'Caveira',
                'description' =>
                    'Can interrogate any downed attacker - revealing the exact location of every other attacker for 10 seconds and has an ability which makes her footstps completely silent',
                'side' => 'Defense',
                'year' => 1,
                'season' => 3,
            ],
            [
                'name' => 'Clash',
                'description' =>
                    'Has an electrified shield which she can use to tase and slow down attackers',
                'side' => 'Defense',
                'year' => 3,
                'season' => 3,
            ],
            [
                'name' => 'Deimos',
                'description' =>
                    'Can choose any defender and track their location. While the hunt is active, his location is also revealed to the hunted defender.',
                'side' => 'Attack',
                'year' => 9,
                'season' => 1,
            ],
            [
                'name' => 'Doc',
                'description' =>
                    'Has a stim pistol which can heal or even revive downed people',
                'side' => 'Defense',
                'year' => 0,
                'season' => 5,
            ],
            [
                'name' => 'Dokkaebi',
                'description' =>
                    "Can call attackers' phone, causing them to buzz incessantly until they reset it manually and can also hack defenders' cameras",
                'side' => 'Attack',
                'year' => 2,
                'season' => 4,
            ],
            [
                'name' => 'Echo',
                'description' =>
                    'Has 2 hovering drones (called Yokai), which can stick to flat surfaces on a ceiling and cloak, as well as emit ultrasonic bursts that can disorient the enemy',
                'side' => 'Defense',
                'year' => 1,
                'season' => 4,
            ],
            [
                'name' => 'Ela',
                'description' =>
                    'Has 3 Grzmot Mines - concussive mines that disorient any who come within range',
                'side' => 'Defense',
                'year' => 2,
                'season' => 3,
            ],
            [
                'name' => 'Fenrir',
                'description' =>
                    'When activated, his gadget produces a cloud of smoke that limits the vision of any operator - except him - caught into it',
                'side' => 'Defense',
                'year' => 8,
                'season' => 2,
            ],
            [
                'name' => 'Finka',
                'description' =>
                    'Can trigger an Adrenal Surge - allowing affected allies and herself to gain a temporary boost in health - it can also revive downed attackers',
                'side' => 'Attack',
                'year' => 3,
                'season' => 1,
            ],
            [
                'name' => 'Flores',
                'description' => 'Has 4 explosive drones',
                'side' => 'Attack',
                'year' => 6,
                'season' => 1,
            ],
            [
                'name' => 'Frost',
                'description' =>
                    'Can put down 3 bear traps that can be used to incapacitate enemies',
                'side' => 'Defense',
                'year' => 1,
                'season' => 1,
            ],
            [
                'name' => 'Fuze',
                'description' =>
                    'Has 3 Cluster Charges capable of clearing rooms by deploying 5 sub-grenades through destructible structures such as walls, floors, and windows.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 8,
            ],
            [
                'name' => 'Glaz',
                'description' =>
                    'The thermal feedback of his marksman rifle enables him to outline long-range marks, even through smoke for easier shooting',
                'side' => 'Attack',
                'year' => 0,
                'season' => 7,
            ],
            [
                'name' => 'Goyo',
                'description' =>
                    'Can put up 5 Volcán Canisters that detonate when fired upon, leaving a carpet of flames for 20 seconds',
                'side' => 'Defense',
                'year' => 4,
                'season' => 3,
            ],
            [
                'name' => 'Gridlock',
                'description' =>
                    'Has 3 Trax Stingers, devices that when deployed, unfold and spread in its proximity. Defenders walking on Trax Stingers are slowed down and take damage.',
                'side' => 'Attack',
                'year' => 4,
                'season' => 1,
            ],
            [
                'name' => 'Grim',
                'description' =>
                    'His Kawan Hive Launcher fires a cannister containing bee-robots that ping the location of any defenders caught within the swarm',
                'side' => 'Attack',
                'year' => 7,
                'season' => 3,
            ],
            [
                'name' => 'Hibana',
                'description' =>
                    'Her X-KAIROS Launcher fires explosive pellets that can be remotely detonated simultaneously and pierce any destructible surface (reinforced or not)',
                'side' => 'Attack',
                'year' => 1,
                'season' => 4,
            ],
            [
                'name' => 'Iana',
                'description' =>
                    'When activated, her Gemini Replicator creates a holographic decoy of her, which she can control remotely - allowing her to scout ahead.',
                'side' => 'Attack',
                'year' => 5,
                'season' => 1,
            ],
            [
                'name' => 'IQ',
                'description' =>
                    'Her gadget is mounted on her pistol and can detect electronic devices from distance - and ping them for her allies',
                'side' => 'Attack',
                'year' => 0,
                'season' => 10,
            ],
            [
                'name' => 'Jackal',
                'description' =>
                    'Can track enemy footprints and can identify its targets',
                'side' => 'Attack',
                'year' => 2,
                'season' => 1,
            ],
            [
                'name' => 'Jäger',
                'description' =>
                    'Has 3 ADS - devices that intercept any explosive projectile thrown by attackers before they detonate and then go on a cooldown for 10 seconds before reactivating.',
                'side' => 'Defense',
                'year' => 0,
                'season' => 9,
            ],
            [
                'name' => 'Kaid',
                'description' =>
                    'His two Electroclaws that can be thrown onto any surface it can attach to, electrifying metal objects within a certain radius of the gadget',
                'side' => 'Defense',
                'year' => 3,
                'season' => 4,
            ],
            [
                'name' => 'Kali',
                'description' =>
                    'Her sniper rifle is equipped with an under-barrel launcher that fires explosive lances. They burrow into any type of wall and explode on both sides - destroying any gadget within the radius of the explosion',
                'side' => 'Attack',
                'year' => 4,
                'season' => 4,
            ],
            [
                'name' => 'Kapkan',
                'description' =>
                    'His tripwires can anchor on doorway and window frames, detonating when enemies trigger its laser',
                'side' => 'Defense',
                'year' => 0,
                'season' => 7,
            ],
            [
                'name' => 'Lesion',
                'description' =>
                    "He comes equipped with his Gu mines: auto-cloaking poison mines. When an attacker steps on it, they'll gradually lose health until they are downed or the needle is removed",
                'side' => 'Defense',
                'year' => 2,
                'season' => 3,
            ],
            [
                'name' => 'Lion',
                'description' =>
                    'His EE-ONE-D flies up above the map to scan for enemy movement at his command. If a Defender moves during this scan, the drone will detect that movement and ping them repeatedly',
                'side' => 'Attack',
                'year' => 3,
                'season' => 1,
            ],
            [
                'name' => 'Maestro',
                'description' =>
                    'His two Evil Eyes are bulletproof, gyroscopic cameras that can fire laser shots',
                'side' => 'Defense',
                'year' => 3,
                'season' => 2,
            ],
            [
                'name' => 'Maverick',
                'description' =>
                    'His torch can make holes in any breachable surface, including reinforced walls, creating new lines of sight silently.',
                'side' => 'Attack',
                'year' => 3,
                'season' => 3,
            ],
            [
                'name' => 'Melusi',
                'description' =>
                    'When deployed a Banshee will emit a loud buzzing noise whenever an Attacker enters its radius and line of sight. It also slows down their movement speed.',
                'side' => 'Defense',
                'year' => 5,
                'season' => 2,
            ],
            [
                'name' => 'Mira',
                'description' =>
                    'She punch her Black Mirrors through walls to create a one-way view to the other side.',
                'side' => 'Defense',
                'year' => 2,
                'season' => 1,
            ],
            [
                'name' => 'Montagne',
                'description' =>
                    'His extendable shield can fully cover him from head-to-toe while standing, providing extra protection that no other shield in the game offers.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 6,
            ],
            [
                'name' => 'Mozzie',
                'description' =>
                    'He is armed with the Pest Launcher, a slingshot that launches small robots (called "Pests") that can hack the attacking team\'s drones and make them his own.',
                'side' => 'Defense',
                'year' => 4,
                'season' => 1,
            ],
            [
                'name' => 'Mute',
                'description' =>
                    'Has 4 signal disruptors which jams communications for remotely detonated gadgets such as breach charges or drones. ',
                'side' => 'Defense',
                'year' => 0,
                'season' => 2,
            ],
            [
                'name' => 'Nøkk',
                'description' =>
                    'When activated, her "HEL Presence Reduction Device" dampens her sound and erases her image from cameras. This, however, comes with some limitations.',
                'side' => 'Attack',
                'year' => 4,
                'season' => 2,
            ],
            [
                'name' => 'Nomad',
                'description' =>
                    'Her gun is equipped with an Airjab launcher which can shoot out up to 3 repulsion grenades that will stick to any surface and knock down any defender in its effective radius when triggered.',
                'side' => 'Attack',
                'year' => 3,
                'season' => 4,
            ],
            [
                'name' => 'Oryx',
                'description' =>
                    'While dashing, he can smash through barricades and unreinforced walls. He can also knock down any Attacker he dashes into as well as vaulting up though any open hatches.',
                'side' => 'Defense',
                'year' => 5,
                'season' => 1,
            ],
            [
                'name' => 'Osa',
                'description' =>
                    'Can carry a transparent bulleproof shield or deploy it on floors or window frames, giving her and her team a protective line of sight',
                'side' => 'Attack',
                'year' => 6,
                'season' => 3,
            ],
            [
                'name' => 'Pulse',
                'description' =>
                    'His cardiac sensor detects the heartbeats of nearby enemies through obstacles.',
                'side' => 'Defense',
                'year' => 0,
                'season' => 4,
            ],
            [
                'name' => 'Ram',
                'description' =>
                    'Her four deployable mini-tanks destroys all breakable surfaces and devices in its way along a set path.',
                'side' => 'Attack',
                'year' => 8,
                'season' => 3,
            ],
            [
                'name' => 'Rauora',
                'description' =>
                    'She can deploy bulletproof panels on doorways',
                'side' => 'Attack',
                'year' => 10,
                'season' => 1,
            ],
            [
                'name' => 'Rook',
                'description' =>
                    'His 5 armor plates can be picked up and equipped for added damage resistance. Any operator with an armor plate is guaranteed to be downed (and not instantly killed) when hit by body shots.',
                'side' => 'Defense',
                'year' => 0,
                'season' => 6,
            ],
            [
                'name' => 'Sens',
                'description' =>
                    'After being thrown, their gadget rolls and drops small projectors to create a screen of light along its path.',
                'side' => 'Attack',
                'year' => 7,
                'season' => 2,
            ],
            [
                'name' => 'Sentry',
                'description' =>
                    'He can pick two of any Defender secondary gadgets (Barbed wire, Bulletproof cam, Deployable shield, Observation blocker, Impact grenade, Nitro cell, Proximity alarm)',
                'side' => 'Defense',
                'year' => 9,
                'season' => 2,
            ],
            [
                'name' => 'Skopós',
                'description' =>
                    'She can switch control between 2 robots: the active one plays like a normal operator, while the idle one acts as a bulletproof camera',
                'side' => 'Defense',
                'year' => 9,
                'season' => 3,
            ],
            [
                'name' => 'Sledge',
                'description' =>
                    'His hammer can breach through almost all non-reinforced surfaces and barricades. It can also destroy defenders gadgets.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 1,
            ],
            [
                'name' => 'Smoke',
                'description' =>
                    'Has 3 three remotely detonated gas canisters that will damage and possibly kill whoever it comes in contact with.',
                'side' => 'Defense',
                'year' => 0,
                'season' => 1,
            ],
            [
                'name' => 'Solis',
                'description' =>
                    "When activated, her gadget lets her see, mark and ping attackers' electronic gadgets - even through walls",
                'side' => 'Defense',
                'year' => 7,
                'season' => 4,
            ],
            [
                'name' => 'Striker',
                'description' =>
                    'She can pick two of any Attacker secondary gadgets (Breach/Hardbreach charge, Claymore, Frag/Impact EMP/Smoke/Stun grenades)',
                'side' => 'Attack',
                'year' => 9,
                'season' => 2,
            ],
            [
                'name' => 'Tachanka',
                'description' =>
                    'Has an incendiary grenade launcher and his primary weapon can easily destroy soft surfaces',
                'side' => 'Defense',
                'year' => 0,
                'season' => 8,
            ],
            [
                'name' => 'Thatcher',
                'description' =>
                    'Has 3 EMP grenades which disable all electronics that are within its area of effect.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 2,
            ],
            [
                'name' => 'Thunderbird',
                'description' =>
                    "Can deploy 3 automated turrets which shoots out healing shots, similar to Doc's Stim Pistol.",
                'side' => 'Defense',
                'year' => 6,
                'season' => 2,
            ],
            [
                'name' => 'Thermite',
                'description' =>
                    'His exothermic charges can breach through any reinforced wall within three seconds of activation.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 4,
            ],
            [
                'name' => 'Thorn',
                'description' =>
                    'The Razorbloom Shell sticks to any surface and detonates after a short fuse time when an Attacker steps into its detection radius. The damage inflicted can be lethal.',
                'side' => 'Defense',
                'year' => 6,
                'season' => 4,
            ],
            [
                'name' => 'Tubarão',
                'description' =>
                    'The Zoto Canister is a throwable device that can slow down enemies, freeze devices, and even leave footprints behind if the frozen area is walked on',
                'side' => 'Defense',
                'year' => 8,
                'season' => 4,
            ],
            [
                'name' => 'Twitch',
                'description' =>
                    'Her two Shock Drones can take out enemy gadgets from afar and shock enemies.',
                'side' => 'Attack',
                'year' => 0,
                'season' => 5,
            ],
            [
                'name' => 'Valkyrie',
                'description' =>
                    'Her 3 portable sticky cameras (called "Black Eyes") can attach on almost any surface for increased surveillance.',
                'side' => 'Defense',
                'year' => 1,
                'season' => 2,
            ],
            [
                'name' => 'Vigil',
                'description' =>
                    'When activated, his Video Disruptor makes him invisible to cameras and drones',
                'side' => 'Defense',
                'year' => 2,
                'season' => 4,
            ],
            [
                'name' => 'Wamai',
                'description' =>
                    'His Mag-NETs are powerful electromagnets that intercept and redirect projectiles thrown by attackers.',
                'side' => 'Defense',
                'year' => 4,
                'season' => 4,
            ],
            [
                'name' => 'Warden',
                'description' =>
                    'When activated, his pair of Glance Smart Glasses nullifies the effects of Stun Grenades, Smoke Grenades and other related gadgets.',
                'side' => 'Defense',
                'year' => 4,
                'season' => 2,
            ],
            [
                'name' => 'Ying',
                'description' =>
                    'Has 3 Candelas, specialized timed explosives which can either be thrown or anchored onto a deployable surface to shoot out six flash charges into an area.',
                'side' => 'Attack',
                'year' => 2,
                'season' => 3,
            ],
            [
                'name' => 'Zero',
                'description' =>
                    'Can shoot cameras that can lodge themselves into any surface to surveil either side and shoot a laser that can destroy enemy gadgets.',
                'side' => 'Attack',
                'year' => 5,
                'season' => 3,
            ],
            [
                'name' => 'Zofia',
                'description' =>
                    'Has a double barrel launcher that can launch either impact grenades or concussion grenades.',
                'side' => 'Attack',
                'year' => 2,
                'season' => 3,
            ],
        ];

        $operators = [];

        foreach ($data as $op) {
            $operators[] = [
                'id' => Str::ulid()->toBase32(),
                'name' => $op['name'],
                'description' => $op['description'],
                'side' => $op['side'],
                'year' => $op['year'],
                'season' => $op['season'],
                'operation_id' =>
                    $op['year'] > 0 ? "Y{$op['year']}S{$op['season']}" : 'Y1S0',
            ];
        }

        Operator::insert($operators);
    }
}
