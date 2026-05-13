## ADDED Requirements

### Requirement: Generate OpenSpec markdown from PRD
Sistem SHALL mampu memproses input form (teks mentah PRD) dari pengguna, mengirimkannya ke model OpenAI via API, dan mengonversinya menjadi teks format OpenSpec markdown yang valid.

#### Scenario: User submits PRD text successfully
- **WHEN** user menekan tombol submit pada form input PRD di halaman dashboard dengan teks yang valid
- **THEN** sistem memanggil OpenAI dan merespons dengan format markdown terstruktur
- **THEN** sistem menyimpan hasil markdown tersebut ke dalam database `projects` pada kolom `spec_content`
- **THEN** sistem mengarahkan ulang pengguna ke `/openspec?project={id}`
