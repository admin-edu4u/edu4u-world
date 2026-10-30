import { FooterWrapper } from "@/components/FooterWrapper";
import { HeaderWrapper } from "@/components/HeaderWrapper";
import { LegacySiteScripts } from "@/components/LegacySiteScripts";
import { LocaleProvider } from "@/components/LocaleProvider";
import { SiteModals } from "@/components/SiteModals";
import { getDictionary } from "@/lib/i18n/dictionaries";
import { getLocale } from "@/lib/i18n/locale";
import { getSiteSettings } from "@/lib/siteSettings";

export const dynamic = "force-dynamic";

export default async function SiteLayout({
  children,
}: {
  children: React.ReactNode;
}) {
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
        {children}
        <FooterWrapper />
        <SiteModals />
        <LegacySiteScripts />
      </div>
    </LocaleProvider>
  );
}
