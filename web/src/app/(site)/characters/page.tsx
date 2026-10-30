import { createPublicClient } from "@/lib/supabase/server";
import CharactersClient from "./CharactersClient";

export default async function CharactersPage() {
  const supabase = createPublicClient();
  const { data: qualifications } = await supabase
    .from("qualification_level")
    .select("q_id, q_level")
    .eq("q_status", 1)
    .order("q_id");

  return <CharactersClient qualifications={qualifications ?? []} />;
}
