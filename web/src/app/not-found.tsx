import { HeaderWrapper } from "@/components/HeaderWrapper";
import { LegacySiteScripts } from "@/components/LegacySiteScripts";
import { LocaleProvider } from "@/components/LocaleProvider";
import { SiteFooter } from "@/components/SiteFooter";
import { SiteModals } from "@/components/SiteModals";
import { SiteNotFound } from "@/components/SiteNotFound";
import { getDictionary } from "@/lib/i18n/dictionaries";
import { getLocale } from "@/lib/i18n/locale";
import { getSiteSettings } from "@/lib/siteSettings";

export default async function RootNotFound() {
  const locale = await getLocale();
  const [dictionary, settings] = await Promise.all([
    getDictionary(locale),
    getSiteSettings(),
  ]);

  return (
    <LocaleProvider
      locale={locale}
      dictionary={dictionary}
      flags={{ careersHidden: settings.careersHidden }}
    >
      <div className="d-flex flex-column">
        <HeaderWrapper />
        <SiteNotFound />
        <SiteFooter />
        <SiteModals />
        <LegacySiteScripts />
      </div>
    </LocaleProvider>
  );
}
