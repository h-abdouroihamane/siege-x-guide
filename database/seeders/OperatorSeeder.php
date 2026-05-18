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
                'year' => 5,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y5S2',
            ],
            [
                'name' => 'Alibi',
                'description' =>
                    'Deploys three holograms which reveal the location and the identity of the attacker who shoots through it',
                'year' => 3,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y3S2',
            ],
            [
                'name' => 'Amaru',
                'description' =>
                    'Can quickly propel herself through windows/hatches and on top of buildings with her grappling hook',
                'year' => 4,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y4S3',
            ],
            [
                'name' => 'Aruni',
                'description' =>
                    'Can place laser gates that deal damage to anyone passing through. They can be disabled for 30s by throwing any projectile through them',
                'year' => 5,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y5S4',
            ],
            [
                'name' => 'Ash',
                'description' =>
                    'Her gadget can shoot up to 2 Breaching Rounds, which will burrow into a surface and detonate automatically',
                'year' => 0,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Azami',
                'description' =>
                    'Has up to 5 Kiba Barriers - modified kunais that sticks to a surface after being thrown and release a material that creates a barrier',
                'year' => 7,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y7S1',
            ],
            [
                'name' => 'Bandit',
                'description' =>
                    'Can put down batteries which electrifies walls or barb wire - destroying any electrified gadget',
                'year' => 0,
                'season' => 10,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Blackbeard',
                'description' =>
                    'Has a shield with a retractable bulletproof glass that slides down so he can shoot while still using his shield',
                'year' => 1,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y1S2',
            ],
            [
                'name' => 'Blitz',
                'description' => 'Flashes people with his shield',
                'year' => 0,
                'season' => 9,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Brava',
                'description' =>
                    "Has a drone that can hack defenders' electronic gadgets and depending of the gadget: take control of it or turn it back against defenders or simply destroy it",
                'year' => 8,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y8S1',
            ],
            [
                'name' => 'Buck',
                'description' => 'Has an under-barrel shotgun',
                'year' => 1,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y1S1',
            ],
            [
                'name' => 'Capitão',
                'description' =>
                    'Has a crossbow which can shoot two fire bolts and two smoke bolts',
                'year' => 1,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y1S3',
            ],
            [
                'name' => 'Castle',
                'description' =>
                    'Can put up 4 reinforced barricades which can only be taken down with explosive devices',
                'year' => 0,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Caveira',
                'description' =>
                    'Can interrogate any downed attacker - revealing the exact location of every other attacker for 10 seconds and has an ability which makes her footstps completely silent',
                'year' => 1,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y1S3',
            ],
            [
                'name' => 'Clash',
                'description' =>
                    'Has an electrified shield which she can use to tase and slow down attackers',
                'year' => 3,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y3S3',
            ],
            [
                'name' => 'Deimos',
                'description' =>
                    'Can choose any defender and track their location. While the hunt is active, his location is also revealed to the hunted defender',
                'year' => 9,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y9S1',
            ],
            [
                'name' => 'Doc',
                'description' =>
                    'Has a stim pistol which can heal or even revive downed people',
                'year' => 0,
                'season' => 5,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Dokkaebi',
                'description' =>
                    "Can target a defender and call their phone, causing them to buzz until they hang up: if they don't hang up in time, the phone explodes. She can also temporarily hack defenders' observation tools.",
                'year' => 2,
                'season' => 4,
                'side' => 'Attack',
                'operation_id' => 'Y2S4',
            ],
            [
                'name' => 'Echo',
                'description' =>
                    'Has 2 hovering drones which can stick to flat surfaces on a ceiling and cloak, as well as emit ultrasonic bursts that can disorient the enemy',
                'year' => 1,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y1S4',
            ],
            [
                'name' => 'Ela',
                'description' =>
                    'Has 3 Grzmot Mines - concussive mines that disorient any who come within range',
                'year' => 2,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y2S3',
            ],
            [
                'name' => 'Fenrir',
                'description' =>
                    'When activated, his gadget produces a cloud of smoke that limits the vision of any operator - except him - caught into it',
                'year' => 8,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y8S2',
            ],
            [
                'name' => 'Finka',
                'description' =>
                    'Can trigger an Adrenal Surge - allowing affected allies and herself to gain a temporary boost in health - it can also revive downed attackers',
                'year' => 3,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y3S1',
            ],
            [
                'name' => 'Flores',
                'description' => 'Has 4 explosive drones',
                'year' => 6,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y6S1',
            ],
            [
                'name' => 'Frost',
                'description' =>
                    'Can put down 3 bear traps that can be used to incapacitate enemies',
                'year' => 1,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y1S1',
            ],
            [
                'name' => 'Fuze',
                'description' =>
                    'Has 3 Cluster Charges who when activated,each launch 5 explosive pellets on the other side',
                'year' => 0,
                'season' => 8,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Glaz',
                'description' =>
                    'Has a sniper equipped with a thermal scope that allows him to see people through smoke',
                'year' => 0,
                'season' => 7,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Goyo',
                'description' =>
                    'Can put up 2 Volcán Canisters that detonate when fired upon, leaving a carpet of flames for 20 seconds',
                'year' => 4,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y4S3',
            ],
            [
                'name' => 'Gridlock',
                'description' =>
                    'Has 3 Trax Stingers, devices that when deployed, unfold and spread in its proximity. Defenders walking on Trax Stingers are slowed down and take damage',
                'year' => 4,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y4S1',
            ],
            [
                'name' => 'Grim',
                'description' =>
                    'His Kawan Hive Launcher fires a cannister containing bee-robots that ping the location of any defenders caught within the swarm',
                'year' => 7,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y7S3',
            ],
            [
                'name' => 'Hibana',
                'description' =>
                    'Her X-KAIROS Launcher fires explosive pellets that can be remotely detonated simultaneously and pierce any destructible surface (reinforced or not)',
                'year' => 1,
                'season' => 4,
                'side' => 'Attack',
                'operation_id' => 'Y1S4',
            ],
            [
                'name' => 'Iana',
                'description' =>
                    'When activated, her Gemini Replicator creates a holographic decoy of her, which she can control remotely - allowing her to scout ahead',
                'year' => 5,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y5S1',
            ],
            [
                'name' => 'IQ',
                'description' =>
                    'Her gadget is mounted on her pistol and can detect electronic devices from distance - and ping them for her allies',
                'year' => 0,
                'season' => 10,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Jackal',
                'description' =>
                    'Can track enemy footprints and can identify its targets',
                'year' => 2,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y2S1',
            ],
            [
                'name' => 'Jäger',
                'description' =>
                    'Has 3 ADS - devices that intercept any explosive projectile thrown by attackers before they detonate and then go on a cooldown for 10 seconds before reactivating',
                'year' => 0,
                'season' => 9,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Kaid',
                'description' =>
                    'His two Electroclaws that can be thrown onto any surface it can attach to, electrifying metal objects within a certain radius of the gadget',
                'year' => 3,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y3S4',
            ],
            [
                'name' => 'Kali',
                'description' =>
                    'Her sniper rifle is equipped with an under-barrel launcher that fires explosive lances. They burrow into any type of wall and explode on both sides - destroying any gadget within the radius of the explosion',
                'year' => 4,
                'season' => 4,
                'side' => 'Attack',
                'operation_id' => 'Y4S4',
            ],
            [
                'name' => 'Kapkan',
                'description' =>
                    'His tripwires can anchor on doorway and window frames, detonating when enemies trigger its laser',
                'year' => 0,
                'season' => 7,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Lesion',
                'description' =>
                    'He comes equipped with his Gu mines. When an attacker steps on it, they are poisoned and gradually lose health until they are downed or the needle is removed',
                'year' => 2,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y2S3',
            ],
            [
                'name' => 'Lion',
                'description' =>
                    'His EE-ONE-D flies up above the map to scan for enemy movement at his command. If a Defender moves during this scan, the drone will detect that movement and ping them repeatedly',
                'year' => 3,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y3S1',
            ],
            [
                'name' => 'Maestro',
                'description' =>
                    'His two Evil Eyes are bulletproof, gyroscopic cameras that can fire laser shots',
                'year' => 3,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y3S2',
            ],
            [
                'name' => 'Maverick',
                'description' =>
                    'His torch can make holes in any breachable surface, including reinforced walls, creating new lines of sight silently',
                'year' => 3,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y3S3',
            ],
            [
                'name' => 'Melusi',
                'description' =>
                    'When deployed a Banshee will emit a loud buzzing noise whenever an Attacker enters its radius and line of sight. It also slows down their movement speed',
                'year' => 5,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y5S2',
            ],
            [
                'name' => 'Mira',
                'description' =>
                    'She punch her Black Mirrors through walls to create a one-way view to the other side',
                'year' => 2,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y2S1',
            ],
            [
                'name' => 'Montagne',
                'description' =>
                    'His extendable shield can fully cover him from head-to-toe while standing, providing extra protection that no other shield in the game offers',
                'year' => 0,
                'season' => 6,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Mozzie',
                'description' =>
                    "He is armed with the Pest Launcher, a slingshot that launches small robots that can hack the attacking team's drones and make them his own",
                'year' => 4,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y4S1',
            ],
            [
                'name' => 'Mute',
                'description' =>
                    'Has 4 signal disruptors which jams communications for remotely detonated gadgets such as breach charges or drones',
                'year' => 0,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Nøkk',
                'description' =>
                    'When activated, her gadget make her invisble to cameras',
                'year' => 4,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y4S2',
            ],
            [
                'name' => 'Nomad',
                'description' =>
                    'Her gun is equipped with an Airjab launcher which can shoot out up to 3 repulsion grenades that will stick to any surface and knock down any defender in its radius when triggered',
                'year' => 3,
                'season' => 4,
                'side' => 'Attack',
                'operation_id' => 'Y3S4',
            ],
            [
                'name' => 'Oryx',
                'description' =>
                    'While dashing, he can smash through barricades and unreinforced walls. He can also knock down any Attacker he dashes into as well as vaulting up though any open hatches',
                'year' => 5,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y5S1',
            ],
            [
                'name' => 'Osa',
                'description' =>
                    'Can carry a transparent bulleproof shield that she can deploy on floors or window frames',
                'year' => 6,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y6S3',
            ],
            [
                'name' => 'Pulse',
                'description' =>
                    'His cardiac sensor detects the heartbeats of nearby enemies through obstacles',
                'year' => 0,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Ram',
                'description' =>
                    'Her four deployable mini-tanks destroys all breakable surfaces and devices in its way along a set path',
                'year' => 8,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y8S3',
            ],
            [
                'name' => 'Rauora',
                'description' =>
                    'She can deploy bulletproof panels on doorways',
                'year' => 10,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y10S1',
            ],
            [
                'name' => 'Rook',
                'description' =>
                    'His armor pack gives him and his team a health boost as well as the ability of reviving themselves if not instantly killed by a headshot',
                'year' => 0,
                'season' => 6,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Sens',
                'description' =>
                    'After being thrown, their gadget rolls and drops small projectors to create a screen of light along its path',
                'year' => 7,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y7S2',
            ],
            [
                'name' => 'Sentry',
                'description' =>
                    'He can pick two of any Defender secondary gadgets (Barbed wire, Bulletproof cam, Deployable shield, Observation blocker, Impact grenade, Nitro cell, Proximity alarm)',
                'year' => 9,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y9S2',
            ],
            [
                'name' => 'Skopós',
                'description' =>
                    'She can switch control between 2 robots: the active one plays like a normal operator, while the idle one acts as a bulletproof camera',
                'year' => 9,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y9S3',
            ],
            [
                'name' => 'Sledge',
                'description' =>
                    'His hammer can breach through almost all non-reinforced surfaces and barricades. It can also destroy defenders gadgets',
                'year' => 0,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Smoke',
                'description' =>
                    'Has 3 three remotely detonated poisoned gas canisters',
                'year' => 0,
                'season' => 1,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Solis',
                'description' =>
                    "When activated, her gadget lets her see, mark and ping attackers' electronic gadgets - even through walls",
                'year' => 7,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y7S4',
            ],
            [
                'name' => 'Striker',
                'description' =>
                    'She can pick two of any Attacker secondary gadgets (Breach/Hardbreach charge, Claymore, Frag/Impact EMP/Smoke/Stun grenades)',
                'year' => 9,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y9S2',
            ],
            [
                'name' => 'Tachanka',
                'description' =>
                    'Has an incendiary grenade launcher and his primary weapon can easily destroy soft surfaces',
                'year' => 0,
                'season' => 8,
                'side' => 'Defense',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Thatcher',
                'description' =>
                    'His gadget allows him to detect enemy electronic devices through walls and deactivate them with an EMP blast',
                'year' => 0,
                'season' => 2,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Thunderbird',
                'description' =>
                    "Can deploy 3 automated turrets which shoots out healing shots, similar to Doc's Stim Pistol",
                'year' => 6,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y6S2',
            ],
            [
                'name' => 'Thermite',
                'description' =>
                    'His exothermic charges can breach through any reinforced wall',
                'year' => 0,
                'season' => 4,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Thorn',
                'description' =>
                    'Her gadget sticks to any surface and detonates after a short fuse time when an Attacker steps into its detection radius. The damage inflicted can be lethal',
                'year' => 6,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y6S4',
            ],
            [
                'name' => 'Tubarão',
                'description' =>
                    'His gadget is a throwable device that can slow down enemies, freeze devices, and even leave footprints behind if the frozen area is walked on',
                'year' => 8,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y8S4',
            ],
            [
                'name' => 'Twitch',
                'description' =>
                    'Her two Shock Drones can take out enemy gadgets from afar and shock enemies',
                'year' => 0,
                'season' => 5,
                'side' => 'Attack',
                'operation_id' => 'Y1S0',
            ],
            [
                'name' => 'Valkyrie',
                'description' =>
                    'Her 3 portable sticky cameras can attach on any surface',
                'year' => 1,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y1S2',
            ],
            [
                'name' => 'Vigil',
                'description' =>
                    'When activated, his gadget makes him invisible to cameras and drones',
                'year' => 2,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y2S4',
            ],
            [
                'name' => 'Wamai',
                'description' =>
                    'His Mag-NETs are powerful electromagnets that intercept and redirect grenades and projectiles thrown by attackers',
                'year' => 4,
                'season' => 4,
                'side' => 'Defense',
                'operation_id' => 'Y4S4',
            ],
            [
                'name' => 'Warden',
                'description' =>
                    'When activated, his gadget nullifies the effects of Stun Grenades and let him see through Smoke Grenades',
                'year' => 4,
                'season' => 2,
                'side' => 'Defense',
                'operation_id' => 'Y4S2',
            ],
            [
                'name' => 'Ying',
                'description' =>
                    'Has 3 Candelas which can either be thrown or anchored onto a deployable surface to shoot out six flash charges into an area',
                'year' => 2,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y2S3',
            ],
            [
                'name' => 'Zero',
                'description' =>
                    'Can shoot cameras that can lodge themselves into any surface to surveil either side and shoot a laser that can destroy enemy gadgets',
                'year' => 5,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y5S3',
            ],
            [
                'name' => 'Zofia',
                'description' =>
                    'Has a double barrel launcher that can launch either impact grenades or concussion grenades',
                'year' => 2,
                'season' => 3,
                'side' => 'Attack',
                'operation_id' => 'Y2S3',
            ],
            [
                'name' => 'Denari',
                'description' =>
                    'His gadget lets him setup a trip laser network that damages and slows down enemies passing through it',
                'year' => 10,
                'season' => 3,
                'side' => 'Defense',
                'operation_id' => 'Y10S3',
            ],
            [
                'name' => 'Solid Snake',
                'description' =>
                    'His radar alerts him of nearby enemies, active default cameras, as well as enemies\' vision cone when Precision Mode is enabled. He can also scavenge secondary gadgets from fallen players.',
                'year' => 11,
                'season' => 1,
                'side' => 'Attack',
                'operation_id' => 'Y11S1',
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
                'operation_id' => $op['operation_id'],
            ];
        }

        Operator::insert($operators);
    }
}
