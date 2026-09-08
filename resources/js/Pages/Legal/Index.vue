<template>
  <MainLayout>
    <div class="min-h-screen bg-slate-50 py-10 sm:py-16">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Banner -->
        <div class="text-center max-w-3xl mx-auto mb-10">
          <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Trust, Safety & Governance
          </div>
          <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Legal & Privacy Center</h1>
          <p class="text-sm text-slate-600 mt-2">
            Clear guidelines, transparent privacy protections, and user agreements for the Kidapawan City student housing community.
          </p>
          <div class="flex items-center justify-center gap-4 mt-4 text-xs text-slate-500">
            <span>Last Updated: September 2026</span>
            <span>•</span>
            <span>Applicable to: Students, Property Owners & Visitors</span>
          </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl p-2 border border-slate-200/80 shadow-xs mb-8 flex flex-wrap sm:flex-nowrap gap-2">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            @click="switchTab(tab.id)"
            :class="activeTab === tab.id
              ? 'bg-indigo-600 text-white shadow-sm font-bold'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
            class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
          >
            <component :is="tab.icon" class="w-4 h-4 shrink-0" />
            <span>{{ tab.name }}</span>
          </button>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10 text-slate-700 leading-relaxed space-y-8">
          
          <!-- 1. PRIVACY POLICY -->
          <section v-if="activeTab === 'privacy'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl font-black text-slate-900 tracking-tight">Privacy Policy</h2>
              <p class="text-xs text-slate-500 mt-1">In compliance with Republic Act No. 10173 (Philippine Data Privacy Act of 2012)</p>
            </div>

            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-950 flex items-start gap-3">
              <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="font-bold mb-1">Our Commitment to Student and Landlord Privacy</p>
                <p>KidaBoard is committed to protecting the confidentiality and security of personal information collected through our accommodation platform in Kidapawan City. We adhere to the principles of transparency, legitimate purpose, and proportionality.</p>
              </div>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">1. Personal Information We Collect</h3>
              <p class="text-sm mb-3">To facilitate secure boarding house listings, student room reservations, and appointment bookings, we collect the following categories of data:</p>
              <ul class="list-disc pl-6 space-y-1.5 text-xs sm:text-sm text-slate-600">
                <li><strong class="text-slate-800">Student Tenants:</strong> Full legal name, official school email address, active mobile number, academic affiliation/student ID, emergency contact or guardian details, and payment transaction receipts/reference numbers.</li>
                <li><strong class="text-slate-800">Boarding House Owners:</strong> Full legal name, government-issued identification, phone number, property location address, business/barangay permits, and digital payment receiving details (e.g., GCash / Maya account names and numbers).</li>
                <li><strong class="text-slate-800">Technical Logs:</strong> IP address, browser user agent, session timestamps, and device type for fraud prevention and CSRF protection.</li>
              </ul>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">2. Purpose of Data Processing</h3>
              <p class="text-sm mb-3">Your personal data is processed strictly for the following legitimate purposes:</p>
              <ul class="list-disc pl-6 space-y-1.5 text-xs sm:text-sm text-slate-600">
                <li>Verifying user identities via Email One-Time Passwords (OTP) and administrative reviews.</li>
                <li>Dispatching viewing appointment requests and reservation notifications between students and boarding house owners.</li>
                <li>Allowing property owners to confirm downpayment receipts and manage room occupancy records.</li>
                <li>Generating administrative audits and reports to ensure housing compliance in Kidapawan City.</li>
              </ul>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">3. Data Sharing & Third Parties</h3>
              <p class="text-sm text-slate-600 leading-relaxed mb-2">
                We do <strong class="text-slate-900">not</strong> sell, rent, or trade your personal information to third-party advertisers or marketers. Data is only shared under the following conditions:
              </p>
              <ul class="list-disc pl-6 space-y-1.5 text-xs sm:text-sm text-slate-600">
                <li><strong class="text-slate-800">Between Student and Landlord:</strong> Once a reservation or appointment is requested, the owner receives the student's name and contact number to facilitate the accommodation.</li>
                <li><strong class="text-slate-800">Legal Compliance:</strong> When required by local authorities, municipal law enforcement, or by court order under Philippine law.</li>
              </ul>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">4. Data Retention and Security</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                All passwords are encrypted using strong bcrypt hashing algorithms. Uploaded payment receipts and identification documents are stored on secure private storage drivers. Personal data is retained for the duration of an active user account or tenancy period, plus one (1) year for audit purposes, after which records are permanently deleted or anonymized.
              </p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">5. Your Rights as a Data Subject</h3>
              <p class="text-sm text-slate-600 leading-relaxed mb-2">
                Under the Data Privacy Act of 2012, you have the right to be informed, to access, to object, to modify or rectify inaccurate information, and to request erasure or blocking of your personal data.
              </p>
              <p class="text-xs text-slate-500">
                To exercise these rights or lodge an inquiry, contact our Data Protection Officer at: <a href="mailto:privacy@kidaboard.ph" class="text-indigo-600 font-bold hover:underline">privacy@kidaboard.ph</a>.
              </p>
            </div>
          </section>

          <!-- 2. TERMS AND CONDITIONS -->
          <section v-if="activeTab === 'terms'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl font-black text-slate-900 tracking-tight">Terms and Conditions</h2>
              <p class="text-xs text-slate-500 mt-1">Platform Service Agreement between Users and KidaBoard</p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">1. Acceptance of Terms</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                By registering an account, booking an appointment, or reserving a boarding house room on KidaBoard, you signify your agreement to these Terms and Conditions. If you do not agree, you must cease using the platform immediately.
              </p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">2. Role of KidaBoard</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                KidaBoard serves as an online directory, scheduling facilitator, and reservation portal connecting students with accredited boarding houses in Kidapawan City. KidaBoard is <strong class="text-slate-800">not a real estate broker, landlord, or property management agency</strong>. The formal lease contract is strictly between the student tenant and the boarding house property owner.
              </p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">3. User Responsibilities & Conduct</h3>
              <ul class="list-disc pl-6 space-y-2 text-xs sm:text-sm text-slate-600">
                <li><strong class="text-slate-800">Students:</strong> Must provide accurate student credentials, attend booked viewing appointments punctually, and follow boarding house safety curfews and guidelines set by the landlord.</li>
                <li><strong class="text-slate-800">Boarding House Owners:</strong> Must ensure published photos, amenities, room dimensions, and monthly rental rates accurately reflect actual conditions. Must hold valid barangay and local city business permits.</li>
                <li><strong class="text-slate-800">Prohibited Activities:</strong> Any posting of fraudulent listings, spoofed payment receipts, harassment, offensive content, or attempts to bypass system security will lead to immediate account termination and reporting to the City Administrator's office.</li>
              </ul>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">4. Disclaimers & Limitation of Liability</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                While KidaBoard conducts administrative verifications of registered boarding houses, KidaBoard shall not be liable for any property damage, loss of belongings, physical injury, or personal conflicts arising within the boarding house premises.
              </p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">5. Governing Law</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                These terms are governed by the laws of the Republic of the Philippines. Any legal disputes shall be brought before the proper courts of Kidapawan City, Cotabato.
              </p>
            </div>
          </section>

          <!-- 3. REFUND & CANCELLATION POLICY -->
          <section v-if="activeTab === 'refund'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl font-black text-slate-900 tracking-tight">Refund & Cancellation Policy</h2>
              <p class="text-xs text-slate-500 mt-1">Guidelines on room reservation fees, viewing cancellations, and deposit returns</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                  Eligible for Full Refund
                </h4>
                <p class="text-xs text-emerald-700 leading-relaxed">
                  • Cancellation requested by the student within <strong>24 hours</strong> of submitting the reservation.<br>
                  • Property owner rejects the reservation request.<br>
                  • Discrepancies between room listing details and actual room upon physical inspection.
                </p>
              </div>

              <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                  <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                  Non-Refundable Circumstances
                </h4>
                <p class="text-xs text-amber-700 leading-relaxed">
                  • Student no-show on the intended move-in date without 48-hour prior notice.<br>
                  • Cancellation made after the room has been held and taken off the market for more than 3 calendar days.<br>
                  • Expulsion from property due to violation of house rules or local law.
                </p>
              </div>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">1. Viewing Appointments</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                Scheduling a boarding house viewing appointment is <strong class="text-slate-900">100% free of charge</strong>. Students may reschedule or cancel appointments at any time through their Student Dashboard at least 2 hours prior to the scheduled viewing slot.
              </p>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">2. Processing of Approved Refunds</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                For reservations paid digitally (GCash, Maya, or bank transfer), refunds approved by the landlord will be reimbursed to the student's originating payment account within 3 to 5 business days. KidaBoard administrators monitor all disputes to safeguard students against unfair forfeiture.
              </p>
            </div>
          </section>

          <!-- 4. COOKIE POLICY -->
          <section v-if="activeTab === 'cookies'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl font-black text-slate-900 tracking-tight">Cookie Policy</h2>
              <p class="text-xs text-slate-500 mt-1">Information about cookies and local browser storage used by KidaBoard</p>
            </div>

            <p class="text-sm text-slate-600 leading-relaxed">
              KidaBoard utilizes browser cookies and local storage tokens to deliver a secure, responsive, and tailored user experience. This policy explains what cookies are saved on your computer or mobile device and how you can manage them.
            </p>

            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                    <th class="py-2.5 px-3">Cookie / Storage Key</th>
                    <th class="py-2.5 px-3">Type</th>
                    <th class="py-2.5 px-3">Duration</th>
                    <th class="py-2.5 px-3">Purpose</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                  <tr>
                    <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">kidaboard_session</td>
                    <td class="py-2.5 px-3"><span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">Strictly Necessary</span></td>
                    <td class="py-2.5 px-3">Session / 2 hours</td>
                    <td class="py-2.5 px-3">Maintains student, landlord, and admin authenticated login state.</td>
                  </tr>
                  <tr>
                    <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">XSRF-TOKEN</td>
                    <td class="py-2.5 px-3"><span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">Security</span></td>
                    <td class="py-2.5 px-3">Session</td>
                    <td class="py-2.5 px-3">Prevents Cross-Site Request Forgery (CSRF) attacks when submitting forms.</td>
                  </tr>
                  <tr>
                    <td class="py-2.5 px-3 font-mono font-bold text-indigo-700">kidaboard_cookie_consent</td>
                    <td class="py-2.5 px-3"><span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-200">Functional</span></td>
                    <td class="py-2.5 px-3">1 Year</td>
                    <td class="py-2.5 px-3">Remembers your cookie banner acknowledgment preferences.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div>
              <h3 class="text-base font-bold text-slate-900 mb-2">Managing Your Cookie Preferences</h3>
              <p class="text-sm text-slate-600 leading-relaxed mb-3">
                You can adjust your cookie settings at any time using your web browser options (Chrome, Safari, Firefox, Edge). Please note that disabling strictly necessary cookies will prevent you from signing in or completing room reservations.
              </p>
              <button
                type="button"
                @click="resetCookieBanner"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition cursor-pointer"
              >
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Reset Cookie Preferences
              </button>
            </div>
          </section>

          <!-- Footer Actions & Print Support -->
          <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div class="text-slate-500">
              Questions regarding these policies? Email <a href="mailto:support@kidaboard.ph" class="text-indigo-600 font-bold hover:underline">support@kidaboard.ph</a>
            </div>
            <div class="flex items-center gap-3">
              <button
                type="button"
                @click="printPage"
                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition flex items-center gap-1.5 cursor-pointer"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print / Save PDF
              </button>
              <a
                href="/"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-xs"
              >
                Back to Home
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, h } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
  initialTab: {
    type: String,
    default: 'privacy',
  },
});

const activeTab = ref(props.initialTab || 'privacy');

// Simple SVG Icon helper
const ShieldIcon = () => h('svg', { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' })
]);

const DocumentIcon = () => h('svg', { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' })
]);

const CurrencyIcon = () => h('svg', { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' })
]);

const CookieIcon = () => h('svg', { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '2' }, [
  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' })
]);

const tabs = [
  { id: 'privacy', name: 'Privacy Policy', icon: ShieldIcon },
  { id: 'terms', name: 'Terms & Conditions', icon: DocumentIcon },
  { id: 'refund', name: 'Refund Policy', icon: CurrencyIcon },
  { id: 'cookies', name: 'Cookie Policy', icon: CookieIcon },
];

function switchTab(id) {
  activeTab.value = id;
  const pathMap = {
    privacy: '/privacy-policy',
    terms: '/terms',
    refund: '/refund-policy',
    cookies: '/cookie-policy',
  };
  if (pathMap[id]) {
    window.history.replaceState({}, '', pathMap[id]);
  }
}

function printPage() {
  window.print();
}

function resetCookieBanner() {
  localStorage.removeItem('kidaboard_cookie_consent');
  window.dispatchEvent(new Event('kidaboard:reset-cookies'));
  alert('Cookie preferences have been reset. The cookie banner will appear on the next page interaction.');
}
</script>
