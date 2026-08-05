@extends('layouts.app')

@section('title', 'New Schedule — StressLab')

@section('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        margin-bottom: 0.3rem;
    }
    .page-header h1 em { font-style: normal; color: var(--pink); }
    .page-header p { font-size: 0.83rem; color: var(--text-muted); }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1rem;
        border-radius: 9px;
        background: var(--white);
        border: 1px solid var(--gray-2);
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s, background 0.15s;
        white-space: nowrap;
    }
    .btn-back:hover { border-color: var(--pink-soft); background: var(--pink-lt); color: var(--pink); }

    /* ── LAYOUT ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
    }
    .form-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--gray-2);
        background: var(--gray-1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .form-card-icon {
        width: 32px; height: 32px;
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.88rem;
        color: var(--pink);
        flex-shrink: 0;
    }
    .form-card-title { font-size: 0.88rem; font-weight: 700; color: var(--text-primary); }
    .form-card-subtitle { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.1rem; }
    .form-body { padding: 1.5rem; }

    /* ── FORM GROUPS ── */
    .form-group { margin-bottom: 1.35rem; }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .form-label i { width: 14px; font-size: 0.78rem; color: var(--pink); text-align: center; }
    .required-dot {
        width: 5px; height: 5px;
        background: var(--pink);
        border-radius: 50%;
        display: inline-block;
        margin-left: 2px;
    }

    /* Date & Time inputs */
    .form-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid var(--gray-2);
        border-radius: 9px;
        font-size: 0.85rem;
        font-family: var(--sans);
        color: var(--text-primary);
        background: var(--white);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px rgba(200,24,74,0.1);
    }

    /* Date+Time side-by-side */
    .input-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* Live preview */
    .preview-banner {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--pink-lt);
        border: 1px solid var(--pink-soft);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-top: 1.35rem;
        font-size: 0.82rem;
        color: var(--pink-deep);
        font-weight: 500;
        min-height: 46px;
        transition: opacity 0.2s;
    }
    .preview-banner i { color: var(--pink); flex-shrink: 0; }
    .preview-banner.empty { opacity: 0.5; }

    .field-hint {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.73rem;
        color: var(--text-muted);
        margin-top: 0.4rem;
    }
    .field-hint i { font-size: 0.7rem; }

    .form-divider { height: 1px; background: var(--gray-2); margin: 1.35rem 0; }

    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.8rem;
        background: var(--pink);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: var(--sans);
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(200,24,74,0.28);
        transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        margin-top: 1.5rem;
    }
    .btn-submit:hover {
        background: var(--pink-deep);
        box-shadow: 0 6px 20px rgba(200,24,74,0.38);
        transform: translateY(-1px);
    }
    .btn-submit:active { transform: translateY(0); }

    /* ── SIDE INFO CARD ── */
    .info-card {
        background: var(--white);
        border: 1px solid var(--gray-2);
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: calc(var(--navbar-h) + 1.5rem);
    }
    .info-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-2);
        background: var(--pink-lt);
    }
    .info-card-title { font-size: 0.8rem; font-weight: 700; color: var(--pink-deep); }
    .info-card-body { padding: 1.25rem; }

    /* Mini calendar preview */
    .calendar-preview {
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        border-radius: 11px;
        padding: 1rem;
        text-align: center;
        margin-bottom: 1.1rem;
    }
    .cal-month {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .cal-day {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--pink);
        letter-spacing: -0.04em;
        line-height: 1;
        font-family: var(--mono);
    }
    .cal-weekday {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.2rem;
        font-weight: 500;
    }
    .cal-time {
        font-size: 0.9rem;
        font-weight: 700;
        font-family: var(--mono);
        color: var(--text-primary);
        margin-top: 0.5rem;
    }
    .cal-placeholder {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-style: italic;
    }

    .info-divider { height: 1px; background: var(--gray-2); margin: 1rem 0; }

    .info-tip {
        display: flex;
        gap: 0.6rem;
        background: var(--gray-1);
        border: 1px solid var(--gray-2);
        border-radius: 9px;
        padding: 0.85rem 1rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.55;
    }
    .info-tip i { color: var(--pink); margin-top: 1px; flex-shrink: 0; font-size: 0.8rem; }

    @media (max-width: 820px) {
        .create-layout { grid-template-columns: 1fr; }
        .info-card { position: static; }
        .input-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1>New <em>Schedule</em></h1>
            <p>Create a new date and time slot for a test session.</p>
        </div>
        <a href="/schedules" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Schedules</a>
    </div>

    <div class="create-layout">

        <!-- FORM -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-calendar-plus"></i></div>
                <div>
                    <div class="form-card-title">Schedule Configuration</div>
                    <div class="form-card-subtitle">Set the date and time for this test slot</div>
                </div>
            </div>
            <div class="form-body">
                <form action="/schedules" method="POST" id="scheduleForm">
                    @csrf

                    <div class="input-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="date">
                                <i class="fas fa-calendar-alt"></i>
                                Date
                                <span class="required-dot"></span>
                            </label>
                            <input type="date" id="date" name="date"
                                   class="form-input"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ request('date') }}" required>
                            <div class="field-hint"><i class="fas fa-circle-info"></i> Today or future dates only.</div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="time">
                                <i class="fas fa-clock"></i>
                                Time
                                <span class="required-dot"></span>
                            </label>
                            <input type="time" id="time" name="time"
                                   class="form-input" required>
                            <div class="field-hint"><i class="fas fa-circle-info"></i> Future times if today.</div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="preview-banner empty" id="previewBanner">
                        <i class="fas fa-calendar-check"></i>
                        <span id="previewText">Select a date and time to preview the schedule.</span>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-floppy-disk"></i> Save Schedule
                    </button>
                </form>
            </div>
        </div>

        <!-- SIDE CARD -->
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-title"><i class="fas fa-calendar-days" style="margin-right:0.4rem;"></i>Schedule Preview</div>
            </div>
            <div class="info-card-body">
                <div class="calendar-preview" id="calPreview">
                    <div class="cal-placeholder">Pick a date &amp; time<br>to see a preview here.</div>
                </div>

                <div class="info-divider"></div>

                <div class="info-tip">
                    <i class="fas fa-lightbulb"></i>
                    After saving, you can assign participants to this slot from the schedule's detail page, or directly when creating a new test session.
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const previewBanner = document.getElementById('previewBanner');
            const previewText   = document.getElementById('previewText');
            const calPreview    = document.getElementById('calPreview');

            const monthNames  = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const dayNames    = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

            function padTwo(n) { return String(n).padStart(2, '0'); }

            function formatTime12(timeStr) {
                if (!timeStr) return '';
                const [h, m] = timeStr.split(':').map(Number);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const h12  = h % 12 || 12;
                return `${padTwo(h12)}:${padTwo(m)} ${ampm}`;
            }

            function enforceTimeLimits() {
                const now      = new Date();
                const todayStr = `${now.getFullYear()}-${padTwo(now.getMonth()+1)}-${padTwo(now.getDate())}`;

                if (dateInput.value === todayStr) {
                    const minTime = `${padTwo(now.getHours())}:${padTwo(now.getMinutes())}`;
                    timeInput.min = minTime;
                    if (timeInput.value && timeInput.value < minTime) timeInput.value = '';
                } else {
                    timeInput.removeAttribute('min');
                }
            }

            function updatePreview() {
                enforceTimeLimits();
                const dVal = dateInput.value;
                const tVal = timeInput.value;

                if (dVal && tVal) {
                    // Inline banner
                    const d = new Date(dVal + 'T00:00:00');
                    const dayName  = dayNames[d.getDay()];
                    const dateStr  = `${dayName}, ${padTwo(d.getDate())} ${monthNames[d.getMonth()]} ${d.getFullYear()}`;
                    previewText.textContent = `${dateStr} at ${formatTime12(tVal)}`;
                    previewBanner.classList.remove('empty');

                    // Side calendar
                    calPreview.innerHTML = `
                        <div class="cal-month">${monthNames[d.getMonth()]} ${d.getFullYear()}</div>
                        <div class="cal-day">${padTwo(d.getDate())}</div>
                        <div class="cal-weekday">${dayName}</div>
                        <div class="cal-time">${formatTime12(tVal)}</div>
                    `;
                } else if (dVal) {
                    const d = new Date(dVal + 'T00:00:00');
                    previewText.textContent = `${dayNames[d.getDay()]}, ${padTwo(d.getDate())} ${monthNames[d.getMonth()]} ${d.getFullYear()} — pick a time`;
                    previewBanner.classList.add('empty');

                    calPreview.innerHTML = `
                        <div class="cal-month">${monthNames[d.getMonth()]} ${d.getFullYear()}</div>
                        <div class="cal-day">${padTwo(d.getDate())}</div>
                        <div class="cal-weekday">${dayNames[d.getDay()]}</div>
                        <div class="cal-placeholder" style="margin-top:0.4rem;">Pick a time</div>
                    `;
                } else {
                    previewText.textContent = 'Select a date and time to preview the schedule.';
                    previewBanner.classList.add('empty');
                    calPreview.innerHTML = `<div class="cal-placeholder">Pick a date &amp; time<br>to see a preview here.</div>`;
                }
            }

            dateInput.addEventListener('change', updatePreview);
            timeInput.addEventListener('change', updatePreview);
            if (dateInput.value) updatePreview();
        });
    </script>

@endsection