<?php

include_once "koneksi.php";
include_once "auth_check.php";

/*
 * Hak akses:
 * 1   = Admin
 * 910 = Super Admin
 */
require_user_levels($koneksi, array(1, 910));

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<style>
:root {
    --sensor-primary: #2a3f54;
    --sensor-success: #28a745;
    --sensor-success-soft: #e8f7ed;
    --sensor-danger: #dc3545;
    --sensor-danger-soft: #fff0f1;
    --sensor-warning: #f39c12;
    --sensor-border: #e4e9f0;
    --sensor-text: #25313f;
    --sensor-muted: #6b7785;
    --sensor-background: #f4f6f9;
}

/* =====================================================
   PANEL
===================================================== */
.sensor-panel {
    border: 0;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 5px 24px rgba(42, 63, 84, 0.08);
}

.sensor-panel .x_title {
    margin-bottom: 0;
    padding: 18px 22px;
    border-bottom: 1px solid var(--sensor-border);
}

.sensor-panel .x_title h2 {
    float: none;
    margin: 0;
    color: var(--sensor-primary);
    font-size: 22px;
    font-weight: 700;
}

.sensor-panel .x_content {
    padding: 22px;
    background: var(--sensor-background);
}

/* =====================================================
   SENSOR GRID
===================================================== */
.sensor-grid {
    display: grid;
    grid-template-columns: minmax(0, 900px);
    justify-content: start;
    gap: 22px;
    width: 100%;
}

/* =====================================================
   SENSOR CARD
===================================================== */
.tpms-card {
    position: relative;
    width: 100%;
    overflow: hidden;
    border: 1px solid var(--sensor-border);
    border-left: 7px solid var(--sensor-success);
    border-radius: 20px;
    background: #ffffff;
    box-shadow: 0 8px 28px rgba(42, 63, 84, 0.10);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease;
}

.tpms-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(42, 63, 84, 0.14);
}

.tpms-card.critical {
    border-left-color: var(--sensor-danger);
    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #ffffff 65%,
            #fff4f5 100%
        );
}

/*
 * Tidak lagi membuat seluruh kartu berkedip.
 * Hanya indikator alarm yang diberi animasi.
 */
@keyframes alarmPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.42);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

/* =====================================================
   CARD HEADER
===================================================== */
.tpms-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--sensor-border);
}

.device-information {
    min-width: 0;
}

.tpms-header {
    margin: 0;
    color: var(--sensor-text);
    font-size: 24px;
    font-weight: 800;
    line-height: 1.2;
    word-break: break-word;
}

.tpms-unit {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 7px;
    color: var(--sensor-muted);
    font-size: 14px;
    font-weight: 600;
}

.tpms-unit i {
    color: #5b7c99;
}

.device-state {
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
    gap: 8px;
    padding: 8px 13px;
    border-radius: 30px;
    color: #166534;
    background: var(--sensor-success-soft);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.35px;
}

.device-state.critical-state {
    color: #991b1b;
    background: #fee2e2;
}

.device-state-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--sensor-success);
}

.device-state.critical-state .device-state-dot {
    background: var(--sensor-danger);
    animation: alarmPulse 1.4s infinite;
}

/* =====================================================
   CARD BODY
===================================================== */
.tpms-body {
    display: grid;
    grid-template-columns: 215px minmax(0, 1fr);
    gap: 24px;
    padding: 24px;
}

/* =====================================================
   DEVICE IMAGE
===================================================== */
.tpms-left {
    display: flex;
    min-height: 260px;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border: 1px solid #e7ebf0;
    border-radius: 16px;
    background:
        radial-gradient(
            circle at center,
            #ffffff 0%,
            #f5f8fb 100%
        );
}

.tpms-left img {
    display: block;
    width: 175px;
    max-width: 100%;
    height: auto;
    object-fit: contain;
}

/* =====================================================
   SENSOR DATA
===================================================== */
.tpms-right {
    min-width: 0;
}

.sensor-list {
    display: grid;
    gap: 12px;
}

.sensor-row {
    display: grid;
    grid-template-columns:
        minmax(145px, 1fr)
        minmax(110px, auto)
        minmax(100px, auto);
    align-items: center;
    gap: 14px;
    min-height: 66px;
    padding: 12px 14px;
    border: 1px solid #e8edf2;
    border-radius: 13px;
    background: #fbfcfd;
}

.sensor-row:hover {
    background: #f6f9fb;
}

.sensor-name {
    display: flex;
    align-items: center;
    min-width: 0;
    gap: 11px;
    color: var(--sensor-text);
    font-size: 14px;
    font-weight: 700;
}

.sensor-icon {
    display: inline-flex;
    width: 38px;
    height: 38px;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 10px;
    color: #42627d;
    background: #eaf1f7;
    font-size: 17px;
}

.sensor-value {
    color: var(--sensor-text);
    font-size: 17px;
    font-weight: 800;
    text-align: right;
    white-space: nowrap;
}

/* =====================================================
   STATUS BADGES
===================================================== */
.status-badge {
    display: inline-flex;
    min-width: 96px;
    min-height: 32px;
    align-items: center;
    justify-content: center;
    justify-self: end;
    padding: 6px 11px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    line-height: 1.2;
    text-align: center;
    white-space: nowrap;
}

.status-normal {
    color: #166534;
    background: #dcfce7;
    border: 1px solid #bbf7d0;
}

.status-danger {
    color: #991b1b;
    background: #fee2e2;
    border: 1px solid #fecaca;
}

.status-alarm-off {
    color: #4b5563;
    background: #f1f3f5;
    border: 1px solid #dee2e6;
}

.status-alarm-on {
    color: #ffffff;
    background: var(--sensor-danger);
    border: 1px solid var(--sensor-danger);
    animation: alarmPulse 1.4s infinite;
}

/* =====================================================
   OVERALL STATUS
===================================================== */
.overall-status {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin: 0 24px 20px;
    padding: 15px 18px;
    border-radius: 14px;
}

.overall-status-normal {
    color: #166534;
    background: var(--sensor-success-soft);
    border: 1px solid #bce5c8;
}

.overall-status-danger {
    color: #991b1b;
    background: var(--sensor-danger-soft);
    border: 1px solid #f6c7cc;
}

.overall-label-area {
    display: flex;
    align-items: center;
    gap: 12px;
}

.overall-icon {
    display: inline-flex;
    width: 42px;
    height: 42px;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.75);
    font-size: 19px;
}

.overall-label {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
}

.overall-value {
    margin-top: 3px;
    font-size: 17px;
    font-weight: 800;
}

.overall-indicator {
    flex-shrink: 0;
    font-size: 23px;
}

/* =====================================================
   TIMESTAMP
===================================================== */
.tpms-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 14px 24px;
    border-top: 1px solid var(--sensor-border);
    color: var(--sensor-muted);
    background: #fafbfd;
    font-size: 12px;
}

.tpms-timestamp {
    display: flex;
    align-items: center;
    gap: 7px;
}

.live-indicator {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #28784a;
    font-weight: 700;
}

.live-indicator-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--sensor-success);
}

/* =====================================================
   AUDIO NOTICE
===================================================== */
.audio-notice {
    display: none;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
    padding: 12px 15px;
    color: #7a5300;
    background: #fff8df;
    border: 1px solid #f6dc8c;
    border-radius: 10px;
    cursor: pointer;
}

.audio-notice i {
    margin-right: 7px;
}

/* =====================================================
   LOADING, ERROR, EMPTY
===================================================== */
.sensor-message {
    width: 100%;
    padding: 55px 20px;
    border: 1px dashed #ccd5df;
    border-radius: 15px;
    color: var(--sensor-muted);
    background: #ffffff;
    text-align: center;
}

.sensor-message i {
    display: block;
    margin-bottom: 13px;
    color: #7890a6;
    font-size: 32px;
}

.sensor-message h3 {
    margin: 0 0 7px;
    color: var(--sensor-text);
    font-size: 19px;
    font-weight: 700;
}

.sensor-message p {
    margin: 0;
}

.sensor-error i,
.sensor-error h3 {
    color: var(--sensor-danger);
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 900px) {
    .tpms-body {
        grid-template-columns: 180px minmax(0, 1fr);
    }

    .tpms-left img {
        width: 145px;
    }

    .sensor-row {
        grid-template-columns:
            minmax(135px, 1fr)
            minmax(95px, auto)
            minmax(90px, auto);
    }
}

@media (max-width: 700px) {
    .sensor-panel .x_content {
        padding: 14px;
    }

    .tpms-card-header {
        align-items: flex-start;
        padding: 18px;
    }

    .tpms-header {
        font-size: 21px;
    }

    .tpms-body {
        grid-template-columns: 1fr;
        gap: 16px;
        padding: 18px;
    }

    .tpms-left {
        min-height: 180px;
    }

    .tpms-left img {
        width: 145px;
    }

    .sensor-row {
        grid-template-columns: 1fr auto;
        gap: 9px 12px;
    }

    .sensor-name {
        grid-column: 1 / 2;
    }

    .sensor-value {
        grid-column: 2 / 3;
    }

    .sensor-row .status-badge {
        grid-column: 1 / 3;
        width: 100%;
        justify-self: stretch;
    }

    .overall-status {
        align-items: flex-start;
        margin: 0 18px 18px;
    }

    .overall-indicator {
        display: none;
    }

    .tpms-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 18px;
    }
}

@media (max-width: 420px) {
    .tpms-card-header {
        flex-direction: column;
    }

    .device-state {
        align-self: flex-start;
    }

    .sensor-value {
        font-size: 15px;
    }
}
</style>

<body class="nav-md">

<div class="container body">
    <div class="main_container">

        <?php include('template_menu.php'); ?>

        <div class="top_nav">
            <div class="nav_menu">

                <div class="nav toggle">
                    <a id="menu_toggle">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <h3>
                            <a style="margin-right: 20px;">
                                LIVE DATA ●
                                <?php echo date("l, Y-m-d"); ?>
                            </a>
                        </h3>
                    </li>
                </ul>

            </div>
        </div>

        <?php if (isset($name) && $name != "") { ?>

            <div class="right_col" role="main">
                <div class="row">

                    <div class="x_panel sensor-panel">

                        <div class="x_title">
                            <h2>
                                <i class="fa fa-line-chart"></i>
                                Industrial Sensor Monitoring
                            </h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <div
                                id="audioNotice"
                                class="audio-notice"
                                onclick="unlockAlarmAudio()"
                            >
                                <span>
                                    <i class="fa fa-volume-up"></i>
                                    Klik di sini untuk mengaktifkan suara alarm.
                                </span>

                                <i class="fa fa-chevron-right"></i>
                            </div>

                            <div
                                class="sensor-grid"
                                id="panelContainer"
                            >
                                <div class="sensor-message">
                                    <i class="fa fa-spinner fa-spin"></i>

                                    <h3>Loading data sensor...</h3>

                                    <p>
                                        Mengambil data terbaru dari perangkat.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        <?php } ?>

    </div>
</div>

<script src="../vendors/jquery/dist/jquery.min.js"></script>
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<script>
"use strict";

/* =====================================================
   KONFIGURASI API
===================================================== */
const API_URL = "api_dummy.php";
const REFRESH_INTERVAL = 3000;
const ALARM_INTERVAL = 5000;

/* =====================================================
   AUDIO
===================================================== */
const alarmAudio = new Audio(
    "https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg"
);

alarmAudio.preload = "auto";
alarmAudio.volume = 1;

let audioUnlocked = false;
let currentAlarmStatus = false;
let lastAlarmTime = 0;
let isLoadingData = false;

/* =====================================================
   AKTIVASI AUDIO
===================================================== */
function unlockAlarmAudio() {
    if (audioUnlocked) {
        hideAudioNotice();
        return;
    }

    alarmAudio.currentTime = 0;

    alarmAudio.play()
        .then(function () {
            alarmAudio.pause();
            alarmAudio.currentTime = 0;

            audioUnlocked = true;

            hideAudioNotice();

            if (currentAlarmStatus) {
                playAlarm(true);
            }
        })
        .catch(function () {
            showAudioNotice();
        });
}

document.addEventListener("click", function () {
    if (!audioUnlocked) {
        unlockAlarmAudio();
    }
});

function showAudioNotice() {
    const notice = document.getElementById("audioNotice");

    if (notice) {
        notice.style.display = "flex";
    }
}

function hideAudioNotice() {
    const notice = document.getElementById("audioNotice");

    if (notice) {
        notice.style.display = "none";
    }
}

/* =====================================================
   HELPER
===================================================== */
function isStatusActive(value) {
    return (
        Number(value) === 1 ||
        value === true ||
        String(value).toLowerCase() === "true"
    );
}

function formatNumber(value, decimalPlaces) {
    const decimals =
        decimalPlaces === undefined
            ? 2
            : decimalPlaces;

    const numberValue = Number(value);

    if (!Number.isFinite(numberValue)) {
        return "-";
    }

    return numberValue.toFixed(decimals);
}

function escapeHtml(value) {
    return String(
        value === null || value === undefined
            ? ""
            : value
    )
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function normalizeOverallValue(value) {
    return String(
        value === null || value === undefined
            ? ""
            : value
    )
        .trim()
        .toLowerCase()
        .replace(/_/g, "")
        .replace(/-/g, "")
        .replace(/\s/g, "");
}

function isOverallCritical(value) {
    const normalized = normalizeOverallValue(value);

    return !(
        normalized === "" ||
        normalized === "normal" ||
        normalized === "ok"
    );
}

function formatOverallStatus(value) {
    const normalized = normalizeOverallValue(value);

    const labels = {
        "normal": "NORMAL",
        "ok": "NORMAL",

        "overpressure":
            "OVER PRESSURE",

        "overtemp1":
            "OVER TEMPERATURE 1",

        "overtemperature1":
            "OVER TEMPERATURE 1",

        "overtemp2":
            "OVER TEMPERATURE 2",

        "overtemperature2":
            "OVER TEMPERATURE 2",

        "overtemp1overtemp2":
            "OVER TEMPERATURE 1 & 2",

        "overpressureovertemp1":
            "OVER PRESSURE & TEMPERATURE 1",

        "overpressureovertemp2":
            "OVER PRESSURE & TEMPERATURE 2",

        "overpressureovertemp1overtemp2":
            "OVER PRESSURE & TEMPERATURE 1 & 2",

        "alarm":
            "ALARM ACTIVE"
    };

    if (labels[normalized]) {
        return labels[normalized];
    }

    if (normalized === "") {
        return "UNKNOWN";
    }

    return String(value)
        .replace(/_/g, " ")
        .replace(/-/g, " ")
        .toUpperCase();
}

function createSensorBadge(
    statusValue,
    normalText,
    dangerText
) {
    const active = isStatusActive(statusValue);

    return `
        <span
            class="
                status-badge
                ${active ? "status-danger" : "status-normal"}
            "
        >
            ${escapeHtml(
                active ? dangerText : normalText
            )}
        </span>
    `;
}

function createAlarmBadge(statusValue) {
    const active = isStatusActive(statusValue);

    return `
        <span
            class="
                status-badge
                ${
                    active
                        ? "status-alarm-on"
                        : "status-alarm-off"
                }
            "
        >
            ${active ? "ALARM ON" : "ALARM OFF"}
        </span>
    `;
}

function createSensorRow(
    iconClass,
    label,
    value,
    unit,
    statusValue,
    dangerText
) {
    return `
        <div class="sensor-row">

            <div class="sensor-name">
                <span class="sensor-icon">
                    <i class="${iconClass}"></i>
                </span>

                <span>
                    ${escapeHtml(label)}
                </span>
            </div>

            <div class="sensor-value">
                ${escapeHtml(value)}
                ${escapeHtml(unit)}
            </div>

            ${createSensorBadge(
                statusValue,
                "NORMAL",
                dangerText
            )}

        </div>
    `;
}

function normalizeApiData(data) {
    if (Array.isArray(data)) {
        return data;
    }

    if (data && typeof data === "object") {
        return [data];
    }

    return [];
}

/* =====================================================
   LOAD DATA
===================================================== */
async function loadData() {
    if (isLoadingData) {
        return;
    }

    isLoadingData = true;

    try {
        const separator =
            API_URL.indexOf("?") >= 0
                ? "&"
                : "?";

        const requestUrl =
            API_URL +
            separator +
            "_time=" +
            Date.now();

        const response = await fetch(requestUrl, {
            method: "GET",
            cache: "no-store",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error(
                "HTTP " +
                response.status +
                " " +
                response.statusText
            );
        }

        const result = await response.json();

        if (
            !result ||
            result.status !== "success"
        ) {
            const errorMessage =
                result && result.message
                    ? result.message
                    : "API mengembalikan status gagal.";

            showError(errorMessage);
            stopAlarm();

            return;
        }

        const sensorData = normalizeApiData(
            result.data
        );

        if (sensorData.length === 0) {
            showEmptyData();
            stopAlarm();

            return;
        }

        renderData(sensorData);

    } catch (error) {
        console.error(
            "Gagal mengambil data sensor:",
            error
        );

        showError(
            "Gagal mengambil data sensor. " +
            "Periksa koneksi database dan api_dummy.php."
        );

        stopAlarm();

    } finally {
        isLoadingData = false;
    }
}

/* =====================================================
   RENDER DATA
===================================================== */
function renderData(data) {
    let html = "";
    let hasActiveAlarm = false;

    /*
     * Tetap menampilkan satu device seperti kode sebelumnya.
     */
    data.slice(0, 1).forEach(function (device) {
        const statusPressure = isStatusActive(
            device.status_pressure
        );

        const statusTemp1 = isStatusActive(
            device.status_temp1
        );

        const statusTemp2 = isStatusActive(
            device.status_temp2
        );

        const statusAlarm = isStatusActive(
            device.status_alarm
        );

        const overallCritical = isOverallCritical(
            device.status_overall
        );

        const isCritical =
            statusPressure ||
            statusTemp1 ||
            statusTemp2 ||
            statusAlarm ||
            overallCritical;

        if (statusAlarm) {
            hasActiveAlarm = true;
        }

        const overallText = formatOverallStatus(
            device.status_overall
        );

        const stateLabel =
            isCritical
                ? "CRITICAL"
                : "NORMAL";

        const stateClass =
            isCritical
                ? "critical-state"
                : "";

        const overallClass =
            isCritical
                ? "overall-status-danger"
                : "overall-status-normal";

        const overallIcon =
            isCritical
                ? "fa fa-exclamation-triangle"
                : "fa fa-check-circle";

        const overallIndicator =
            isCritical
                ? "fa fa-warning"
                : "fa fa-check";

        html += `
            <div
                class="
                    tpms-card
                    ${isCritical ? "critical" : ""}
                "
            >

                <div class="tpms-card-header">

                    <div class="device-information">

                        <h3 class="tpms-header">
                            ${escapeHtml(
                                device.device_id || "-"
                            )}
                        </h3>

                        <div class="tpms-unit">
                            <i class="fa fa-industry"></i>

                            <span>
                                Unit:
                                ${escapeHtml(
                                    device.unit || "-"
                                )}
                            </span>
                        </div>

                    </div>

                    <div
                        class="
                            device-state
                            ${stateClass}
                        "
                    >
                        <span class="device-state-dot"></span>

                        ${stateLabel}
                    </div>

                </div>

                <div class="tpms-body">

                    <div class="tpms-left">

                        <img
                            src="images/control panel.png"
                            alt="Industrial Sensor"
                            onerror="
                                this.style.display='none';
                            "
                        >

                    </div>

                    <div class="tpms-right">

                        <div class="sensor-list">

                            ${createSensorRow(
                                "fa fa-thermometer-half",
                                "Temperature 1",
                                formatNumber(
                                    device.temperature,
                                    2
                                ),
                                "°C",
                                device.status_temp1,
                                "OVER TEMP"
                            )}

                            ${createSensorRow(
                                "fa fa-thermometer-half",
                                "Temperature 2",
                                formatNumber(
                                    device.temperature2,
                                    2
                                ),
                                "°C",
                                device.status_temp2,
                                "OVER TEMP"
                            )}

                            ${createSensorRow(
                                "fa fa-tachometer",
                                "Pressure",
                                formatNumber(
                                    device.pressure,
                                    2
                                ),
                                "psi",
                                device.status_pressure,
                                "OVER PRESS"
                            )}

                            <div class="sensor-row">

                                <div class="sensor-name">
                                    <span class="sensor-icon">
                                        <i class="fa fa-bell"></i>
                                    </span>

                                    <span>
                                        Alarm
                                    </span>
                                </div>

                                <div class="sensor-value">
                                    ${
                                        statusAlarm
                                            ? "Active"
                                            : "Inactive"
                                    }
                                </div>

                                ${createAlarmBadge(
                                    device.status_alarm
                                )}

                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="
                        overall-status
                        ${overallClass}
                    "
                >

                    <div class="overall-label-area">

                        <span class="overall-icon">
                            <i class="${overallIcon}"></i>
                        </span>

                        <div>
                            <div class="overall-label">
                                OVERALL STATUS
                            </div>

                            <div class="overall-value">
                                ${escapeHtml(overallText)}
                            </div>
                        </div>

                    </div>

                    <span class="overall-indicator">
                        <i class="${overallIndicator}"></i>
                    </span>

                </div>

                <div class="tpms-footer">

                    <div class="tpms-timestamp">
                        <i class="fa fa-clock-o"></i>

                        <span>
                            Last update:
                            ${escapeHtml(
                                device.timestamp || "-"
                            )}
                        </span>
                    </div>

                    <div class="live-indicator">
                        <span class="live-indicator-dot"></span>

                        Auto refresh 3 detik
                    </div>

                </div>

            </div>
        `;
    });

    document.getElementById(
        "panelContainer"
    ).innerHTML = html;

    currentAlarmStatus = hasActiveAlarm;

    if (hasActiveAlarm) {
        playAlarm(false);
    } else {
        stopAlarm();
    }
}

/* =====================================================
   ALARM
===================================================== */
function playAlarm(forcePlay) {
    if (!currentAlarmStatus) {
        return;
    }

    if (!audioUnlocked) {
        showAudioNotice();
        return;
    }

    const currentTime = Date.now();

    const canPlay =
        forcePlay ||
        currentTime - lastAlarmTime >=
            ALARM_INTERVAL;

    if (!canPlay) {
        return;
    }

    alarmAudio.pause();
    alarmAudio.currentTime = 0;

    alarmAudio.play()
        .then(function () {
            lastAlarmTime = currentTime;
        })
        .catch(function () {
            audioUnlocked = false;
            showAudioNotice();
        });
}

function stopAlarm() {
    currentAlarmStatus = false;

    alarmAudio.pause();
    alarmAudio.currentTime = 0;
}

/* =====================================================
   ERROR DAN EMPTY
===================================================== */
function showError(message) {
    document.getElementById(
        "panelContainer"
    ).innerHTML = `
        <div class="sensor-message sensor-error">

            <i class="fa fa-exclamation-circle"></i>

            <h3>Gagal memuat data</h3>

            <p>
                ${escapeHtml(message)}
            </p>

        </div>
    `;
}

function showEmptyData() {
    document.getElementById(
        "panelContainer"
    ).innerHTML = `
        <div class="sensor-message sensor-empty">

            <i class="fa fa-database"></i>

            <h3>Data sensor belum tersedia</h3>

            <p>
                Belum ada data terbaru dari perangkat.
            </p>

        </div>
    `;
}

/* =====================================================
   AUTO REFRESH
===================================================== */
window.addEventListener("load", function () {
    loadData();

    window.setInterval(function () {
        loadData();
    }, REFRESH_INTERVAL);
});
</script>

</body>
</html>