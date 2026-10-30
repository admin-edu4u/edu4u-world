import { createPublicClient, createServiceClient } from "@/lib/supabase/server";

export type SiteSettings = {
  careersHidden: boolean;
};

const DEFAULTS: SiteSettings = {
  careersHidden: false,
};

function mapRow(row: { careers_hidden?: boolean | null } | null | undefined): SiteSettings {
  if (!row) return { ...DEFAULTS };
  return {
    careersHidden: Boolean(row.careers_hidden),
  };
}

export async function getSiteSettings(): Promise<SiteSettings> {
  try {
    const supabase = createPublicClient();
    const { data } = await supabase
      .from("site_settings")
      .select("careers_hidden")
      .eq("id", 1)
      .maybeSingle();
    return mapRow(data);
  } catch {
    return { ...DEFAULTS };
  }
}

export async function getSiteSettingsAdmin(): Promise<SiteSettings> {
  const supabase = createServiceClient();
  const { data } = await supabase
    .from("site_settings")
    .select("careers_hidden")
    .eq("id", 1)
    .maybeSingle();
  return mapRow(data);
}
