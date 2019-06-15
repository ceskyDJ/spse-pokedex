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
//            case "":
//                return "";
            default:
                return "Vyskytla se neznámá chyba. Kontaktujte správce.";
        }
    }
}