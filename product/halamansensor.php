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

/* =====================================================
   SENSOR CARD
===================================================== */
.tpms-card {
    border: 4px solid #28a745;
    border-radius: 14px;
    padding: 15px;
    margin-bottom: 20px;
    background-color: #f8f9fa;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transition: all 0.3s ease;
}

.tpms-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 18px rgba(0, 0, 0, 0.16);
}

/* Kondisi critical */
.tpms-card.critical {
    border-color: #dc3545;
    background-color: #ff4d4d;
    color: #ffffff;
    animation: criticalBlink 1s infinite;
}

@keyframes criticalBlink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.65;
    }

    100% {
        opacity: 1;
    }
}

/* =====================================================
   HEADER CARD
===================================================== */
.tpms-header {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
}

.tpms-unit {
    margin-top: 4px;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #6c757d;
}

.tpms-card.critical .tpms-header,
.tpms-card.critical .tpms-unit {
    color: #ffffff;
}

/* =====================================================
   CONTENT CARD
===================================================== */
.tpms-body {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 15px;
}

.tpms-left {
    flex-shrink: 0;
}

.tpms-left img {
    width: 120px;
    max-width: 100%;
    height: auto;
}

.tpms-right {
    width: 100%;
}

.tpms-table {
    width: 100%;
    border-collapse: collapse;
}

.tpms-table td {
    padding: 6px 4px;
    font-size: 14px;
    vertical-align: middle;
}

.tpms-table td:first-child {
    min-width: 115px;
    font-weight: 600;
}

.tpms-table td:nth-child(2) {
    white-space: nowrap;
    font-weight: bold;
}

.tpms-table td:last-child {
    text-align: right;
}

/* =====================================================
   STATUS BADGE
===================================================== */
.status-badge {
    display: inline-block;
    min-width: 75px;
    padding: 4px 8px;
    border-radius: 14px;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
}

.status-normal {
    color: #ffffff;
    background-color: #28a745;
}

.status-danger {
    color: #ffffff;
    background-color: #dc3545;
}

.status-alarm-off {
    color: #ffffff;
    background-color: #6c757d;
}

.status-alarm-on {
    color: #721c24;
    background-color: #ffffff;
}

/* Badge tetap terbaca ketika card berwarna merah */
.tpms-card.critical .status-normal {
    color: #155724;
    background-color: #d4edda;
}

.tpms-card.critical .status-danger {
    color: #721c24;
    background-color: #ffffff;
}

.tpms-card.critical .status-alarm-off {
    color: #343a40;
    background-color: #ffffff;
}

/* =====================================================
   OVERALL STATUS
===================================================== */
.overall-status {
    margin-top: 15px;
    padding: 10px;
    border-radius: 9px;
    text-align: center;
}

.overall-status-normal {
    color: #155724;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
}

.overall-status-danger {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

.tpms-card.critical .overall-status {
    color: #721c24;
    background-color: #ffffff;
    border-color: #ffffff;
}

.overall-label {
    font-size: 11px;
    font-weight: 600;
}

.overall-value {
    margin-top: 3px;
    font-size: 16px;
    font-weight: bold;
}

/* =====================================================
   TIMESTAMP
===================================================== */
.tpms-timestamp {
    margin-top: 12px;
    padding-top: 9px;
    border-top: 1px solid #dee2e6;
    font-size: 12px;
    color: #6c757d;
}

.tpms-card.critical .tpms-timestamp {
    color: #ffffff;
    border-top-color: rgba(255, 255, 255, 0.55);
}

/* =====================================================
   LOADING, ERROR, EMPTY
===================================================== */
.sensor-message {
    width: 100%;
    padding: 35px 15px;
    text-align: center;
}

.sensor-message h3 {
    margin-top: 0;
}

.sensor-error {
    color: #dc3545;
}

.sensor-empty {
    color: #6c757d;
}

/* =====================================================
   AUDIO NOTICE
===================================================== */
.audio-notice {
    display: none;
    margin-bottom: 15px;
    padding: 12px 15px;
    color: #856404;
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    border-radius: 7px;
    cursor: pointer;
}

.audio-notice i {
    margin-right: 5px;
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 767px) {

    .tpms-body {
        flex-direction: column;
        align-items: center;
    }

    .tpms-left img {
        width: 105px;
    }

    .tpms-table td {
        font-size: 13px;
    }

    .tpms-table td:first-child {
        min-width: 100px;
    }
}

</style>

<body class="nav-md">

<div class="container body">
    <div class="main_container">

        <?php include('template_menu.php'); ?>

        <!-- TOP NAVIGATION -->
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

                    <div class="x_panel">

                        <div class="x_title">
                            <h2>Industrial Sensor Monitoring</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <!-- Informasi untuk mengaktifkan audio -->
                            <div
                                id="audioNotice"
                                class="audio-notice"
                                onclick="unlockAlarmAudio()"
                            >
                                <i class="fa fa-volume-up"></i>
                                Klik di sini untuk mengaktifkan suara alarm.
                            </div>

                            <!-- Container sensor -->
                            <div class="row" id="panelContainer">

                                <div class="col-md-12">
                                    <div class="sensor-message">
                                        <h3>Loading data sensor...</h3>
                                    </div>
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

/*
 * Refresh data setiap 3 detik.
 */
const REFRESH_INTERVAL = 3000;

/*
 * Alarm dapat diputar kembali setelah 5 detik.
 */
const ALARM_INTERVAL = 5000;

/* =====================================================
   VARIABEL GLOBAL
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

            console.log("Audio alarm berhasil diaktifkan.");

            if (currentAlarmStatus) {
                playAlarm(true);
            }
        })
        .catch(function (error) {

            console.warn(
                "Browser belum mengizinkan audio:",
                error
            );

            showAudioNotice();
        });
}

/*
 * Browser mengharuskan interaksi pengguna
 * sebelum audio dapat diputar.
 */
document.addEventListener("click", function () {

    if (!audioUnlocked) {
        unlockAlarmAudio();
    }

});

function showAudioNotice() {

    const notice = document.getElementById("audioNotice");

    if (notice) {
        notice.style.display = "block";
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

    if (decimalPlaces === undefined) {
        decimalPlaces = 2;
    }

    const numberValue = Number(value);

    if (!Number.isFinite(numberValue)) {
        return "-";
    }

    return numberValue.toFixed(decimalPlaces);
}

function escapeHtml(value) {

    return String(value === null || value === undefined ? "" : value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function normalizeOverallValue(value) {

    return String(value === null || value === undefined ? "" : value)
        .trim()
        .toLowerCase()
        .replace(/_/g, "")
        .replace(/-/g, "")
        .replace(/\s/g, "");
}

function isOverallCritical(value) {

    const normalized = normalizeOverallValue(value);

    if (
        normalized === "" ||
        normalized === "normal" ||
        normalized === "ok"
    ) {
        return false;
    }

    return true;
}

function formatOverallStatus(value) {

    const normalized = normalizeOverallValue(value);

    const overallLabels = {
        "normal": "NORMAL",
        "ok": "NORMAL",

        "overpressure": "OVER PRESSURE",

        "overtemp1": "OVER TEMPERATURE 1",
        "overtemperature1": "OVER TEMPERATURE 1",

        "overtemp2": "OVER TEMPERATURE 2",
        "overtemperature2": "OVER TEMPERATURE 2",

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

    if (overallLabels[normalized]) {
        return overallLabels[normalized];
    }

    if (normalized === "") {
        return "UNKNOWN";
    }

    return String(value)
        .replace(/_/g, " ")
        .replace(/-/g, " ")
        .toUpperCase();
}

/* =====================================================
   STATUS BADGE
===================================================== */

function createSensorBadge(
    statusValue,
    normalText,
    dangerText
) {

    if (isStatusActive(statusValue)) {

        return `
            <span class="status-badge status-danger">
                ${escapeHtml(dangerText)}
            </span>
        `;
    }

    return `
        <span class="status-badge status-normal">
            ${escapeHtml(normalText)}
        </span>
    `;
}

function createAlarmBadge(statusValue) {

    if (isStatusActive(statusValue)) {

        return `
            <span class="status-badge status-alarm-on">
                ALARM ON
            </span>
        `;
    }

    return `
        <span class="status-badge status-alarm-off">
            ALARM OFF
        </span>
    `;
}

/* =====================================================
   NORMALISASI DATA API
===================================================== */

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

    /*
     * Mencegah request bertumpuk apabila koneksi lambat.
     */
    if (isLoadingData) {
        return;
    }

    isLoadingData = true;

    try {

        const separator = API_URL.indexOf("?") >= 0
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

        if (!result || result.status !== "success") {

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
     * Hanya menampilkan satu data terbaru.
     *
     * Apabila nantinya API menampilkan banyak device
     * dan semua device ingin ditampilkan, ubah:
     *
     * data.slice(0, 1).forEach(...)
     *
     * menjadi:
     *
     * data.forEach(...)
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

        /*
         * Card menjadi merah apabila salah satu
         * status sensor critical atau overall tidak normal.
         */
        const isCritical =
            statusPressure ||
            statusTemp1 ||
            statusTemp2 ||
            statusAlarm ||
            overallCritical;

        /*
         * Suara alarm hanya mengikuti status_alarm.
         */
        if (statusAlarm) {
            hasActiveAlarm = true;
        }

        const cardClass = isCritical
            ? "critical"
            : "";

        const overallClass = isCritical
            ? "overall-status-danger"
            : "overall-status-normal";

        const overallText = formatOverallStatus(
            device.status_overall
        );

        html += `
            <div class="col-lg-4 col-md-6 col-sm-12">

                <div class="tpms-card ${cardClass}">

                    <div class="tpms-header">
                        ${escapeHtml(
                            device.device_id || "-"
                        )}
                    </div>

                    <div class="tpms-unit">
                        Unit:
                        ${escapeHtml(
                            device.unit || "-"
                        )}
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

                            <table class="tpms-table">

                                <tr>

                                    <td>Temperature 1</td>

                                    <td>
                                        ${formatNumber(
                                            device.temperature,
                                            2
                                        )} °C
                                    </td>

                                    <td>
                                        ${createSensorBadge(
                                            device.status_temp1,
                                            "NORMAL",
                                            "OVER TEMP"
                                        )}
                                    </td>

                                </tr>

                                <tr>

                                    <td>Temperature 2</td>

                                    <td>
                                        ${formatNumber(
                                            device.temperature2,
                                            2
                                        )} °C
                                    </td>

                                    <td>
                                        ${createSensorBadge(
                                            device.status_temp2,
                                            "NORMAL",
                                            "OVER TEMP"
                                        )}
                                    </td>

                                </tr>

                                <tr>

                                    <td>Pressure</td>

                                    <td>
                                        ${formatNumber(
                                            device.pressure,
                                            2
                                        )} psi
                                    </td>

                                    <td>
                                        ${createSensorBadge(
                                            device.status_pressure,
                                            "NORMAL",
                                            "OVER PRESS"
                                        )}
                                    </td>

                                </tr>

                                <tr>

                                    <td>Alarm</td>

                                    <td colspan="2">
                                        ${createAlarmBadge(
                                            device.status_alarm
                                        )}
                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                    <div
                        class="
                            overall-status
                            ${overallClass}
                        "
                    >

                        <div class="overall-label">
                            OVERALL STATUS
                        </div>

                        <div class="overall-value">
                            ${escapeHtml(overallText)}
                        </div>

                    </div>

                    <div class="tpms-timestamp">

                        Last update:
                        ${escapeHtml(
                            device.timestamp || "-"
                        )}

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
   AUDIO ALARM
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
        currentTime - lastAlarmTime >= ALARM_INTERVAL;

    if (!canPlay) {
        return;
    }

    alarmAudio.pause();
    alarmAudio.currentTime = 0;

    alarmAudio.play()
        .then(function () {

            lastAlarmTime = currentTime;

        })
        .catch(function (error) {

            console.warn(
                "Alarm gagal diputar:",
                error
            );

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
   ERROR DAN EMPTY STATE
===================================================== */

function showError(message) {

    document.getElementById(
        "panelContainer"
    ).innerHTML = `
        <div class="col-md-12">

            <div class="sensor-message sensor-error">

                <h3>
                    Gagal memuat data
                </h3>

                <p>
                    ${escapeHtml(message)}
                </p>

            </div>

        </div>
    `;
}

function showEmptyData() {

    document.getElementById(
        "panelContainer"
    ).innerHTML = `
        <div class="col-md-12">

            <div class="sensor-message sensor-empty">

                <h3>
                    Data sensor belum tersedia
                </h3>

            </div>

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