<?php

namespace App\Enum;

enum LocalEnum: string
{
    case LOCAL_01 = 'LA CASA DEL CHANTILLY - SAN FELIPE';
    case LOCAL_02 = 'LA CASA DEL CHANTILLY - CUMBRE';
    case LOCAL_03 = 'LA CASA DEL CHANTILLY - INDEPENDENCIA';
    case LOCAL_04 = 'LA CASA DEL CHANTILLY - VILLA SOL';
    case LOCAL_05 = 'LA CASA DEL CHANTILLY - MINKA';
    case LOCAL_06 = 'LA CASA DEL CHANTILLY - DOMINICOS';
    case LOCAL_07 = 'LA CASA DEL CHANTILLY - HUAMANTANGA';
    case LOCAL_08 = 'LA CASA DEL CHANTILLY - PUENTE PIEDRA';
    case LOCAL_09 = 'LA CASA DEL CHANTILLY - PASCANA';
    case LOCAL_10 = 'LA CASA DEL CHANTILLY - COLLIQUE';
    case LOCAL_11 = 'LA CASA DEL CHANTILLY - STA.LUZMILA';
    case LOCAL_12 = 'LA CASA DEL CHANTILLY - LECAROS';
    case LOCAL_13 = 'LA CASA DEL CHANTILLY - RETABLO';
    case LOCAL_14 = 'LA CASA DEL CHANTILLY - SAN DIEGO';
    case LOCAL_15 = 'LA CASA DEL CHANTILLY - SAN MIGUEL';
    case LOCAL_16 = 'LA CASA DEL CHANTILLY - HABICH';
    case LOCAL_17 = 'LA CASA DEL CHANTILLY - PRO';
    case LOCAL_18 = 'LA CASA DEL CHANTILLY - PLAZA NORTE';
    case LOCAL_19 = 'LA CASA DEL CHANTILLY - CONDOMINIOS';
    case LOCAL_20 = 'LA CASA DEL CHANTILLY - MARAÑON';
    case LOCAL_21 = 'LA CASA DEL CHANTILLY - LAS PRADERAS';

    public function image() 
    {
        return match($this) {
            self::LOCAL_01 => 'locals/sanfelipe.jpg',
            self::LOCAL_02 => 'locals/cumbre.jpg',
            self::LOCAL_03 => 'locals/independencia.jpg',
            self::LOCAL_04 => 'locals/villasol.jpg',
            self::LOCAL_05 => 'locals/minka.jpg',
            self::LOCAL_06 => 'locals/dominicos.jpg',
            self::LOCAL_07 => 'locals/huamantanga.jpg',
            self::LOCAL_08 => 'locals/puentepiedra.jpg',
            self::LOCAL_09 => 'locals/pascana.jpg',
            self::LOCAL_10 => 'locals/collique.jpg',
            self::LOCAL_11 => 'locals/santaluzmila.jpg',
            self::LOCAL_12 => 'locals/lecaros.jpg',
            self::LOCAL_13 => 'locals/retablo.jpg',
            self::LOCAL_14 => 'locals/sandiego.jpg',
            self::LOCAL_15 => 'locals/sanmiguel.jpg',
            self::LOCAL_16 => 'locals/habich.jpg',
            self::LOCAL_17 => 'locals/pro.jpg',
            self::LOCAL_18 => 'locals/plazanorte.jpg',
            self::LOCAL_19 => 'locals/condominio.jpg',
            self::LOCAL_20 => 'locals/maranon.jpg',
            self::LOCAL_21 => 'locals/laspraderas.jpg',
        };
    }

    public function address()
    {
        return match($this) {
            self::LOCAL_01 => 'Urb. SanFelipe Av.Universitaria Norte 10614 Comas Lima',
            self::LOCAL_02 => 'P.J. El Progreso 1er Sector Av. Tupac Amaru 2931 Carabayllo Lima',
            self::LOCAL_03 => 'Urb. Ermitaño Av.Los Pinos 272 Independencia Lima',
            self::LOCAL_04 => 'Av. Central Mza.71 Lote 25A A.H. Laura Caller Los Olivos Lima',
            self::LOCAL_05 => 'Av.Argentina3093 Int.280 Callao C.C. MINKA (Av.01)',
            self::LOCAL_06 => 'Urb. Albino Herrera Av. Bocanegra Esq. Dominicos Mz. A Lt 1 Callao',
            self::LOCAL_07 => 'Av. Panamericana Norte Km.30 Urb.Mega mercado Huamantanga Puente Piedra Lima',
            self::LOCAL_08 => 'Av. Puente Piedra Mz 6 Lt 11 Puente Piedra Lima',
            self::LOCAL_09 => 'Av. Micaela Bastida 135A Dpto. PIS1 - Urb. San Agustin Comas Lima',
            self::LOCAL_10 => 'Av. Revolución N° 1321 Comas Lima',
            self::LOCAL_11 => 'Av. Guillermo de la Fuente N° 368 Urb. Santa Luzmila Comas Lima',
            self::LOCAL_12 => 'Av. Juan Lecaros N° S/N Int. 06 Asoc. de Comerc. del Mcdo. N° 01 Puesto 06 Puente Piedra Lima',
            self::LOCAL_13 => 'Av. Micaela Bastidas N° 905 Urb. Retablo Comas Lima',
            self::LOCAL_14 => 'Jr. María de los Ángeles 664 Urb. San Diego  San Martín de Porres  Lima',
            self::LOCAL_15 => 'Urb. Las Leyendas Av. Los Precursores 330 San Miguel Lima',
            self::LOCAL_16 => 'Av. Eduardo de Habich 475 Urb. Ingeniería San Martin de Porras Lima',
            self::LOCAL_17 => 'Av. Proceres de Huandoy 7883 Urb. Pro Los Olivos Lima',
            self::LOCAL_18 => 'Urb. Las Violetas Cal.Zona D Mz. J Lote. 15 Independencia Lima',
            self::LOCAL_19 => 'C.H Ciudad Sol de Collique Jr. 448 Mz G Lote 4 Comas Lima',
            self::LOCAL_20 => 'Urb. Villa Norte Av. Rio Marañon -646-648-650 Mz J Lote 24 San Martín de Porras Lima',
            self::LOCAL_21 => 'Av. Micaela Bastidas 1462 Cnd. Ciudad Sol de Retablo Comas Lima',
        };
    }

    public function department()
    {
        return match($this) {
            self::LOCAL_01 => 'Lima',
            self::LOCAL_02 => 'Lima',
            self::LOCAL_03 => 'Lima',
            self::LOCAL_04 => 'Lima',
            self::LOCAL_05 => 'Callao',
            self::LOCAL_06 => 'Callao',
            self::LOCAL_07 => 'Lima',
            self::LOCAL_08 => 'Lima',
            self::LOCAL_09 => 'Lima',
            self::LOCAL_10 => 'Lima',
            self::LOCAL_11 => 'Lima',
            self::LOCAL_12 => 'Lima',
            self::LOCAL_13 => 'Lima',
            self::LOCAL_14 => 'Lima',
            self::LOCAL_15 => 'Lima',
            self::LOCAL_16 => 'Lima',
            self::LOCAL_17 => 'Lima',
            self::LOCAL_18 => 'Lima',
            self::LOCAL_19 => 'Lima',
            self::LOCAL_20 => 'Lima',
            self::LOCAL_21 => 'Lima',
        };
    }

    public function province()
    {
        return match($this) {
            self::LOCAL_01 => 'Lima',
            self::LOCAL_02 => 'Lima',
            self::LOCAL_03 => 'Lima',
            self::LOCAL_04 => 'Lima',
            self::LOCAL_05 => 'Callao',
            self::LOCAL_06 => 'Callao',
            self::LOCAL_07 => 'Lima',
            self::LOCAL_08 => 'Lima',
            self::LOCAL_09 => 'Lima',
            self::LOCAL_10 => 'Lima',
            self::LOCAL_11 => 'Lima',
            self::LOCAL_12 => 'Lima',
            self::LOCAL_13 => 'Lima',
            self::LOCAL_14 => 'Lima',
            self::LOCAL_15 => 'Lima',
            self::LOCAL_16 => 'Lima',
            self::LOCAL_17 => 'Lima',
            self::LOCAL_18 => 'Lima',
            self::LOCAL_19 => 'Lima',
            self::LOCAL_20 => 'Lima',
            self::LOCAL_21 => 'Lima',
        };
    }

    public function district()
    {
        return match($this) {
            self::LOCAL_01 => 'San Martín de Porres',
            self::LOCAL_02 => 'Carabayllo',
            self::LOCAL_03 => 'Independencia',
            self::LOCAL_04 => 'Los Olivos',
            self::LOCAL_05 => 'Callao',
            self::LOCAL_06 => 'Callao',
            self::LOCAL_07 => 'Puente Piedra',
            self::LOCAL_08 => 'Puente Piedra',
            self::LOCAL_09 => 'Comas',
            self::LOCAL_10 => 'Comas',
            self::LOCAL_11 => 'Comas',
            self::LOCAL_12 => 'Puente Piedra',
            self::LOCAL_13 => 'Comas',
            self::LOCAL_14 => 'San Martín de Porres',
            self::LOCAL_15 => 'San Miguel',
            self::LOCAL_16 => 'San Martín de Porres',
            self::LOCAL_17 => 'Los Olivos',
            self::LOCAL_18 => 'Independencia',
            self::LOCAL_19 => 'Comas',
            self::LOCAL_20 => 'San Martín de Porres',
            self::LOCAL_21 => 'Comas',
        };
    }

    public function start_time()
    {
        return match($this) {
            self::LOCAL_01 => '08:00',
            self::LOCAL_02 => '08:00',
            self::LOCAL_03 => '08:00',
            self::LOCAL_04 => '08:00',
            self::LOCAL_05 => '08:00',
            self::LOCAL_06 => '08:00',
            self::LOCAL_07 => '08:00',
            self::LOCAL_08 => '08:00',
            self::LOCAL_09 => '08:00',
            self::LOCAL_10 => '08:00',
            self::LOCAL_11 => '08:00',
            self::LOCAL_12 => '08:00',
            self::LOCAL_13 => '08:00',
            self::LOCAL_14 => '08:00',
            self::LOCAL_15 => '08:00',
            self::LOCAL_16 => '08:00',
            self::LOCAL_17 => '08:00',
            self::LOCAL_18 => '08:00',
            self::LOCAL_19 => '08:00',
            self::LOCAL_20 => '08:00',
            self::LOCAL_21 => '08:00',
        };
    }

    public function end_time()
    {
        return match($this) {
            self::LOCAL_01 => '22:00',
            self::LOCAL_02 => '22:00',
            self::LOCAL_03 => '22:00',
            self::LOCAL_04 => '22:00',
            self::LOCAL_05 => '22:00',
            self::LOCAL_06 => '22:00',
            self::LOCAL_07 => '22:00',
            self::LOCAL_08 => '22:00',
            self::LOCAL_09 => '22:00',
            self::LOCAL_10 => '22:00',
            self::LOCAL_11 => '22:00',
            self::LOCAL_12 => '22:00',
            self::LOCAL_13 => '22:00',
            self::LOCAL_14 => '22:00',
            self::LOCAL_15 => '22:00',
            self::LOCAL_16 => '22:00',
            self::LOCAL_17 => '22:00',
            self::LOCAL_18 => '22:00',
            self::LOCAL_19 => '22:00',
            self::LOCAL_20 => '22:00',
            self::LOCAL_21 => '22:00',
        };
    }

    public function link_local()
    {
        return match($this) {
            self::LOCAL_01 => 'https://www.google.com/maps?q=-11.9007501602173,-77.0394668579102',
            self::LOCAL_02 => 'https://www.google.com/maps?q=-11.8782434463501,-77.0184631347656',
            self::LOCAL_03 => 'https://www.google.com/maps?q=-11.997537612915,-77.0538635253906',
            self::LOCAL_04 => 'https://www.google.com/maps?q=-11.958597924174224,-77.075641670813',
            self::LOCAL_05 => 'https://www.google.com/maps?q=-12.048365750678332, -77.11121864563663',
            self::LOCAL_06 => 'https://www.google.com/maps?q=-12.0068762,-77.0992793186556',
            self::LOCAL_07 => 'https://www.google.com/maps?q=-11.864161491394,-77.0745391845703',
            self::LOCAL_08 => 'https://www.google.com/maps?q=-11.864161491394,-77.0755844116211',
            self::LOCAL_09 => 'https://www.google.com/maps?q=-11.9332997,-77.0475603',
            self::LOCAL_10 => 'https://www.google.com/maps?q=-11.9146776199341,-77.0300369262695',
            self::LOCAL_11 => 'https://www.google.com/maps?q=-11.9423065185547,-77.0606918334961',
            self::LOCAL_12 => 'https://www.google.com/maps?q=-11.8656139373779,-77.076416015625',
            self::LOCAL_13 => 'https://www.google.com/maps?q=-11.9284620285034,-77.0565490722656',
            self::LOCAL_14 => 'https://www.google.com/maps?q=-11.945918171035611,-77.08836722512692',
            self::LOCAL_15 => 'https://www.google.com/maps?q=-12.070102771458151,-77.09168663214537',
            self::LOCAL_16 => 'https://www.google.com/maps?q=-12.027268409729,-77.0557708740234',
            self::LOCAL_17 => 'https://www.google.com/maps?q=-11.936596510038822,-77.07311934723646',
            self::LOCAL_18 => 'https://www.google.com/maps?q=-12.0057067871094,-77.0539627075195',
            self::LOCAL_19 => 'https://www.google.com/maps?q=-11.9274454116821,-77.0604629516602',
            self::LOCAL_20 => 'https://www.google.com/maps?q=-11.971312403147204,-77.07170970095832',
            self::LOCAL_21 => 'https://www.google.com/maps?q=-11.9252672195435,-77.0606994628906',
        };
    }

    public function latitud()
    {
        return match($this) {
            self::LOCAL_01 => -11.900750160217300,
            self::LOCAL_02 => -11.878243446350100,
            self::LOCAL_03 => -11.997537612915000,
            self::LOCAL_04 => -11.958597924174224,
            self::LOCAL_05 => -12.048365750678332,
            self::LOCAL_06 => -12.006876200000000,
            self::LOCAL_07 => -11.864161491394000,
            self::LOCAL_08 => -11.864161491394000,
            self::LOCAL_09 => -11.933299700000000,
            self::LOCAL_10 => -11.914677619934100,
            self::LOCAL_11 => -11.942306518554700,
            self::LOCAL_12 => -11.865613937377900,
            self::LOCAL_13 => -11.928462028503400,
            self::LOCAL_14 => -11.945918171035611,
            self::LOCAL_15 => -12.070102771458151,
            self::LOCAL_16 => -12.027268409729000,
            self::LOCAL_17 => -11.936596510038822,
            self::LOCAL_18 => -12.005706787109400,
            self::LOCAL_19 => -11.927445411682100,
            self::LOCAL_20 => -11.971312403147204,
            self::LOCAL_21 => -11.925267219543500,
        };
    }

    public function longitud()
    {
        return match($this) {
            self::LOCAL_01 => -77.039466857910200,
            self::LOCAL_02 => -77.018463134765600,
            self::LOCAL_03 => -77.053863525390600,
            self::LOCAL_04 => -77.075641670813000,
            self::LOCAL_05 => -77.111218645636630,
            self::LOCAL_06 => -77.099279318655600,
            self::LOCAL_07 => -77.074539184570300,
            self::LOCAL_08 => -77.075584411621100,
            self::LOCAL_09 => -77.047560300000000,
            self::LOCAL_10 => -77.030036926269500,
            self::LOCAL_11 => -77.060691833496100,
            self::LOCAL_12 => -77.076416015625000,
            self::LOCAL_13 => -77.056549072265600,
            self::LOCAL_14 => -77.088367225126920,
            self::LOCAL_15 => -77.091686632145370,
            self::LOCAL_16 => -77.055770874023400,
            self::LOCAL_17 => -77.073119347236460,
            self::LOCAL_18 => -77.053962707519500,
            self::LOCAL_19 => -77.060462951660200,
            self::LOCAL_20 => -77.071709700958320,
            self::LOCAL_21 => -77.060699462890600,
        };
    }
}
