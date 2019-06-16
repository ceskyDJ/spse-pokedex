<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * Translator for exception alerts
 *
 * @author Michal ŠMAHEL (ceskyDJ)
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Model
 */
class ExceptionAlertsTranslator
{
    public function translateException(string $originalText): string
    {
        switch ($originalText) {
            case "Some form filed hasn't been filled.":
                return "Některé položky nebyly vyplněny.";
            case "Nick is too short.":
                return "Přezdívka je příliš krátká. Minimální délka činí 3 znaky.";
            case "Passwords don't match.":
                return "Zadaná hesla se neshodují.";
            case "Password is too short.":
                return "Heslo je příliš krátké. Minimální délka je 8 znaků.";
            case "Email isn't valid.":
                return "Formát emailu není správný.";
            case "First and/or last name is too short.":
                return "Křestní jméno a/nebo příjmení je příliš krátké. Minimální délka jména i příjmení činí 3 znaky";
            case "Birth format isn't valid.":
                return "Datum narození nemá správný formát. Použijte datum ve formátu: D. M. YYYY";
            case "Some data isn't OK.":
                return "Zadaná přezdívka a/nebo email je již obsazen(a).";
            case "Nick and/or password isn't valid.":
                return "Zadaná přezdívka a/nebo heslo nejsou správné.";
            case "Specified user doesn't exists.":
                return "Zadaný uživatel neexistuje.";
            case "You have insufficient permissions to do this.":
                return "Pro vykonání této akce nemáte dostatečná oprávnění.";
            case "Name is too short.":
                return "Název je příliš krátký. Minimální délka činí 3 znaky.";
            case "Specified type doesn't exists.":
                return "Zadaný typ pokémona neexistuje";
            case "Some data isn't OK. (type)":
                return "Zadný název je již obsazen.";
            case "Specified pokemon doesn't exists.":
                return "Zadaný pokémon neexistuje";
            case "Official number format isn't valid.":
                return "Číslo v oficiálním pokedexu má chybný formát. Použijte: XXX. Pokud je číslo malé, že třeba před něho napsat dostatek 0";
            case "Pokemon's name is too short.":
                return "Jméno pokémona je příliš krátké. Minimální délka jsou 3 znaky.";
            case "Image URL has bad format.":
                return "URL obrázku pokémona má chybný formát. Použijte tento: http(s)://www.example.com";
            case "Spawn time format isn't valid.":
                return "Čas rození pokémonů má chybný formát. Použijte: H:m";
            case "Some data isn't OK. (pokemon)":
                return "Zadané oficiální číslo, jméno a/nebo adresa obrázku jsou již zabrané.";
            case "Image URL doesn't point to valid image.":
                return "URL adresa obrázku neodkazuje na soubor s obrázkem.";
            case "Specified person already owns the pokemon.":
                return "Vybraný pokémon je již ve vlastnictví dané osoby.";
            default:
                return "Vyskytla se neznámá chyba. Kontaktujte správce.";
        }
    }
}