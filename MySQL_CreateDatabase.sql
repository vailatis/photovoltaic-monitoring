CREATE DATABASE `solar_db`;
USE solar_db;
CREATE TABLE `inverter_commands` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` varchar(14) NOT NULL,
  `ref_table` varchar(30) NOT NULL,
  `command` varchar(15) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idx`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
CREATE TABLE `inverter_data` (
  `timestamp` varchar(12) NOT NULL,
  `grid_voltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `grid_frequency` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ac_output_voltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ac_output_frequency` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ac_output_apparent_power` decimal(5,0) NOT NULL DEFAULT '0',
  `ac_output_active_power` decimal(5,0) NOT NULL DEFAULT '0',
  `output_load_percent` int(4) NOT NULL DEFAULT '0',
  `bus_voltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `battery_capacity` int(4) NOT NULL DEFAULT '0',
  `battery_charging_current` decimal(5,0) NOT NULL DEFAULT '0',
  `battery_discharge_current` decimal(5,0) NOT NULL DEFAULT '0',
  `battery_voltage` decimal(6,2) NOT NULL DEFAULT '0.00',
  `battery_voltage_scc` decimal(6,2) NOT NULL DEFAULT '0.00',
  `heatsink_temperature` int(4) NOT NULL DEFAULT '0',
  `pv_current` decimal(5,0) NOT NULL DEFAULT '0',
  `pv_voltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `charge_code` varchar(3) NOT NULL DEFAULT '000',
  `charge_description` varchar(30) NOT NULL DEFAULT 'Sconosciuto',
  `status_code` varchar(1) NOT NULL DEFAULT '0',
  `status_description` varchar(35) NOT NULL DEFAULT 'Sconosciuto',
  `diode_junction_temp` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_UNIQUE` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `inverter_flags` (
  `timestamp` varchar(12) NOT NULL,
  `flags_bitmask` varchar(15) NOT NULL DEFAULT 'E------D-------',
  `a_buzzer` varchar(1) NOT NULL DEFAULT '0',
  `b_overloadbypass` varchar(1) NOT NULL DEFAULT '0',
  `j_powersaving` varchar(1) NOT NULL DEFAULT '0',
  `k_displaydefaultpage` varchar(1) NOT NULL DEFAULT '0',
  `u_overloadrestart` varchar(1) NOT NULL DEFAULT '0',
  `v_overtemprestart` varchar(1) NOT NULL DEFAULT '0',
  `x_backlighton` varchar(1) NOT NULL DEFAULT '0',
  `y_alarmprimarysource` varchar(1) NOT NULL DEFAULT '0',
  `z_faultcoderecord` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_UNIQUE` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `inverter_fwsn` (
  `timestamp` varchar(12) NOT NULL,
  `flags_bitmask` varchar(60) NOT NULL DEFAULT '------------------------------------------------------------',
  `serialNumber` varchar(18) NOT NULL DEFAULT '------------------',
  `firmware1` varchar(12) NOT NULL DEFAULT '------------',
  `firmware2` varchar(12) NOT NULL DEFAULT '------------',
  `protocol` varchar(8) NOT NULL DEFAULT '--------',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_UNIQUE` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `inverter_settings` (
  `timestamp` varchar(12) NOT NULL,
  `settings_bitmask` varchar(100) NOT NULL DEFAULT '-----NaN-----',
  `GridRatingVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `GridRatingCurrent` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ACOutputRatingVolt` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ACOutputRatingFreq` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ACOutputRatingCurrent` decimal(5,1) NOT NULL DEFAULT '0.0',
  `ACOutputRatingApparentPower` int(4) NOT NULL DEFAULT '0',
  `ACOutputRatingActivePower` int(4) NOT NULL DEFAULT '0',
  `BatteryRatingVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `BatteryReChargeVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `BatteryUnderVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `BatteryBulkVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `BatteryFloatVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `BatteryType` int(4) NOT NULL DEFAULT '0',
  `MaxACChargingCurrent` int(4) NOT NULL DEFAULT '0',
  `MaxChargingCurrent` int(4) NOT NULL DEFAULT '0',
  `InputVoltageRange` int(4) NOT NULL DEFAULT '0',
  `OutputSourcePriority` int(4) NOT NULL DEFAULT '0',
  `ChargerSourcePriority` int(4) NOT NULL DEFAULT '0',
  `ParallelMaxNum` int(4) NOT NULL DEFAULT '0',
  `MachineType` varchar(2) NOT NULL DEFAULT '--',
  `Topology` int(4) NOT NULL DEFAULT '0',
  `OutputMode` int(4) NOT NULL DEFAULT '0',
  `BatteryReDischargeVoltage` decimal(5,1) NOT NULL DEFAULT '0.0',
  `PVOKConditionForParallel` int(4) NOT NULL DEFAULT '0',
  `PVPowerBalance` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_UNIQUE` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `inverter_status` (
  `timestamp` varchar(12) NOT NULL,
  `status_bitmask` varchar(32) NOT NULL DEFAULT '--------------------------------',
  `fault_InverterFault` varchar(1) NOT NULL DEFAULT '0',
  `fault_BusOver` varchar(1) NOT NULL DEFAULT '0',
  `fault_BusUnder` varchar(1) NOT NULL DEFAULT '0',
  `fault_BusSoftFail` varchar(1) NOT NULL DEFAULT '0',
  `warning_LineFail` varchar(1) NOT NULL DEFAULT '0',
  `warning_OPVShort` varchar(1) NOT NULL DEFAULT '0',
  `fault_InverterVoltageTooLow` varchar(1) NOT NULL DEFAULT '0',
  `fault_InverterVoltageTooHigh` varchar(1) NOT NULL DEFAULT '0',
  `faultwarning_OverTemperature` varchar(1) NOT NULL DEFAULT '0',
  `faultwarning_FanLocked` varchar(1) NOT NULL DEFAULT '0',
  `faultwarning_BatteryVoltageHigh` varchar(1) NOT NULL DEFAULT '0',
  `warning_BatteryLowAlarm` varchar(1) NOT NULL DEFAULT '0',
  `warning_BatteryUnderShutdown` varchar(1) NOT NULL DEFAULT '0',
  `faultwarning_OverLoad` varchar(1) NOT NULL DEFAULT '0',
  `warning_EepromFault` varchar(1) NOT NULL DEFAULT '0',
  `fault_InverterOverCurrent` varchar(1) NOT NULL DEFAULT '0',
  `fault_InverterSoftFail` varchar(1) NOT NULL DEFAULT '0',
  `fault_SelfTestFail` varchar(1) NOT NULL DEFAULT '0',
  `fault_OPDCVoltageOver` varchar(1) NOT NULL DEFAULT '0',
  `fault_BatOpen` varchar(1) NOT NULL DEFAULT '0',
  `fault_CurrentSensorFail` varchar(1) NOT NULL DEFAULT '0',
  `fault_BatteryShort` varchar(1) NOT NULL DEFAULT '0',
  `warning_PowerLimit` varchar(1) NOT NULL DEFAULT '0',
  `warning_PVVoltageHigh` varchar(1) NOT NULL DEFAULT '0',
  `warning_MPPTOverloadFault` varchar(1) NOT NULL DEFAULT '0',
  `warning_MPPTOverloadWarning` varchar(1) NOT NULL DEFAULT '0',
  `warning_BatteryTooLowToCharge` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_UNIQUE` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `weather_data` (
  `timestamp` varchar(16) NOT NULL,
  `city_id` int(11) NOT NULL,
  `city` varchar(45) NOT NULL,
  `weather_id` int(11) NOT NULL,
  `weather_text` varchar(60) NOT NULL,
  `temperature` int(11) NOT NULL,
  `humidity` int(11) NOT NULL,
  `pressure` int(11) NOT NULL,
  PRIMARY KEY (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
CREATE DEFINER=`solar`@`%` FUNCTION `FN_GETKWHPRICE`(timestamp VARCHAR(12)) RETURNS decimal(8,5)
BEGIN
   /*
      LUN-VEN   8:00-18:59   --> 0.05475 euro/KWh
      LUN-VEN   19:00-7:59   --> 0.04908 euro/KWh
      SAB-DOM   0:00-23:59   --> 0.04908 euro/KWh
   */
   DECLARE myRES DECIMAL(8,5);
   DECLARE myWEEKDAY INT;
   DECLARE myTIME VARCHAR(5);
   SELECT WEEKDAY(CONCAT(SUBSTRING(timestamp,1,4),'-',SUBSTRING(timestamp,5,2),'-',SUBSTRING(timestamp,7,2))) INTO myWEEKDAY;
   SELECT CONCAT(SUBSTRING(timestamp,9,2),':',SUBSTRING(timestamp,11,2)) INTO myTIME;
   SET myRES = 0.04908;
   IF ((myTIME>='08:00')AND(myTIME<='18:59')AND(myWEEKDAY<=4)) THEN SET myRES = 0.05475; END IF;
RETURN myRES;
END$$
DELIMITER ;

CREATE ALGORITHM=UNDEFINED DEFINER=`solar`@`%` SQL SECURITY DEFINER VIEW `vw_inverter_events` AS select `A`.`timestamp` AS `timestamp_start`,ifnull((select `B`.`timestamp` from `inverter_status` `B` where (`B`.`timestamp` > `A`.`timestamp`) limit 1),'') AS `timestamp_end`,`A`.`status_bitmask` AS `status_bitmask`,`A`.`fault_InverterFault` AS `fault_InverterFault`,`A`.`fault_BusOver` AS `fault_BusOver`,`A`.`fault_BusUnder` AS `fault_BusUnder`,`A`.`fault_BusSoftFail` AS `fault_BusSoftFail`,`A`.`warning_LineFail` AS `warning_LineFail`,`A`.`warning_OPVShort` AS `warning_OPVShort`,`A`.`fault_InverterVoltageTooLow` AS `fault_InverterVoltageTooLow`,`A`.`fault_InverterVoltageTooHigh` AS `fault_InverterVoltageTooHigh`,`A`.`faultwarning_OverTemperature` AS `faultwarning_OverTemperature`,`A`.`faultwarning_FanLocked` AS `faultwarning_FanLocked`,`A`.`faultwarning_BatteryVoltageHigh` AS `faultwarning_BatteryVoltageHigh`,`A`.`warning_BatteryLowAlarm` AS `warning_BatteryLowAlarm`,`A`.`warning_BatteryUnderShutdown` AS `warning_BatteryUnderShutdown`,`A`.`faultwarning_OverLoad` AS `faultwarning_OverLoad`,`A`.`warning_EepromFault` AS `warning_EepromFault`,`A`.`fault_InverterOverCurrent` AS `fault_InverterOverCurrent`,`A`.`fault_InverterSoftFail` AS `fault_InverterSoftFail`,`A`.`fault_SelfTestFail` AS `fault_SelfTestFail`,`A`.`fault_OPDCVoltageOver` AS `fault_OPDCVoltageOver`,`A`.`fault_BatOpen` AS `fault_BatOpen`,`A`.`fault_CurrentSensorFail` AS `fault_CurrentSensorFail`,`A`.`fault_BatteryShort` AS `fault_BatteryShort`,`A`.`warning_PowerLimit` AS `warning_PowerLimit`,`A`.`warning_PVVoltageHigh` AS `warning_PVVoltageHigh`,`A`.`warning_MPPTOverloadFault` AS `warning_MPPTOverloadFault`,`A`.`warning_MPPTOverloadWarning` AS `warning_MPPTOverloadWarning`,`A`.`warning_BatteryTooLowToCharge` AS `warning_BatteryTooLowToCharge` from `inverter_status` `A` where (`A`.`status_bitmask` like '%1%');
CREATE ALGORITHM=UNDEFINED DEFINER=`solar`@`%` SQL SECURITY DEFINER VIEW `vw_inverter_power` AS select `inverter_data`.`timestamp` AS `timestamp`,`inverter_data`.`ac_output_active_power` AS `consumo_watt`,cast((`inverter_data`.`ac_output_active_power` * 0.01667) as decimal(5,0)) AS `consumo_wattora`,cast((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_charging_current`) as decimal(5,0)) AS `accumulato_watt`,cast(((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_charging_current`) * 0.01667) as decimal(5,0)) AS `accumulato_wattora`,cast((`inverter_data`.`pv_voltage` * `inverter_data`.`pv_current`) as decimal(5,0)) AS `produzione_pv_watt`,cast(((`inverter_data`.`pv_voltage` * `inverter_data`.`pv_current`) * 0.01667) as decimal(5,0)) AS `produzione_pv_wattora`,cast((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_discharge_current`) as decimal(5,0)) AS `produzione_batt_watt`,cast(((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_discharge_current`) * 0.01667) as decimal(5,0)) AS `produzione_batt_wattora`,cast((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_charging_current`) as decimal(5,0)) AS `accumulo_batt_watt`,cast(((`inverter_data`.`battery_voltage` * `inverter_data`.`battery_charging_current`) * 0.01667) as decimal(5,0)) AS `accumulo_batt_wattora`,cast(if((`inverter_data`.`status_code` = 'L'),`inverter_data`.`ac_output_active_power`,0) as decimal(5,0)) AS `importazione_watt`,cast(if((`inverter_data`.`status_code` = 'L'),(`inverter_data`.`ac_output_active_power` * 0.01667),0) as decimal(5,0)) AS `importazione_wattora` from `inverter_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`solar`@`%` SQL SECURITY DEFINER VIEW `vw_inverter_power_daily` AS select left(`vw_inverter_power`.`timestamp`,8) AS `timestamp`,cast(min(`vw_inverter_power`.`consumo_watt`) as decimal(5,0)) AS `consumo_wmin`,cast(avg(`vw_inverter_power`.`consumo_watt`) as decimal(5,0)) AS `consumo_wavg`,cast(max(`vw_inverter_power`.`consumo_watt`) as decimal(5,0)) AS `consumo_wmax`,sum(`vw_inverter_power`.`consumo_wattora`) AS `consumo_wh`,cast(min(`vw_inverter_power`.`accumulato_watt`) as decimal(5,0)) AS `acc_wmin`,cast(avg(`vw_inverter_power`.`accumulato_watt`) as decimal(5,0)) AS `acc_wavg`,cast(max(`vw_inverter_power`.`accumulato_watt`) as decimal(5,0)) AS `acc_wmax`,sum(`vw_inverter_power`.`accumulato_watt`) AS `acc_w`,sum(`vw_inverter_power`.`accumulato_wattora`) AS `acc_wh`,cast(min(`vw_inverter_power`.`produzione_pv_watt`) as decimal(5,0)) AS `prod_pv_wmin`,cast(avg(`vw_inverter_power`.`produzione_pv_watt`) as decimal(5,0)) AS `prod_pv_wavg`,cast(max(`vw_inverter_power`.`produzione_pv_watt`) as decimal(5,0)) AS `prod_pv_wmax`,sum(`vw_inverter_power`.`produzione_pv_watt`) AS `prod_pv_w`,sum(`vw_inverter_power`.`produzione_pv_wattora`) AS `prod_pv_wh`,cast(min(`vw_inverter_power`.`produzione_batt_watt`) as decimal(5,0)) AS `prod_batt_wmin`,cast(avg(`vw_inverter_power`.`produzione_batt_watt`) as decimal(5,0)) AS `prod_batt_wavg`,cast(max(`vw_inverter_power`.`produzione_batt_watt`) as decimal(5,0)) AS `prod_batt_wmax`,sum(`vw_inverter_power`.`produzione_batt_wattora`) AS `prod_batt_wh`,cast(min(`vw_inverter_power`.`importazione_watt`) as decimal(5,0)) AS `import_wmin`,cast(avg(`vw_inverter_power`.`importazione_watt`) as decimal(5,0)) AS `import_wavg`,cast(max(`vw_inverter_power`.`importazione_watt`) as decimal(5,0)) AS `import_wmax`,sum(`vw_inverter_power`.`importazione_wattora`) AS `import_wh` from `vw_inverter_power` group by 1;
CREATE ALGORITHM=UNDEFINED DEFINER=`solar`@`%` SQL SECURITY DEFINER VIEW `vw_money_savings_TO_DELETE` AS select `A`.`timestamp` AS `timestamp`,`A`.`consumo_wattora` AS `consumo_wattora`,`A`.`importazione_wattora` AS `importazione_wattora`,(`A`.`consumo_wattora` - `A`.`importazione_wattora`) AS `produzione_wattora`,`FN_GETKWHPRICE`(`A`.`timestamp`) AS `costo_chilowattora` from `vw_inverter_power` `A` order by `A`.`timestamp` desc;
CREATE ALGORITHM=UNDEFINED DEFINER=`solar`@`%` SQL SECURITY DEFINER VIEW `vw_solar_production` AS select left(`inverter_data`.`timestamp`,6) AS `date_yymm`,left(`inverter_data`.`timestamp`,4) AS `date_yy`,substr(`inverter_data`.`timestamp`,5,2) AS `date_mm`,cast(sum(((`inverter_data`.`pv_current` * `inverter_data`.`pv_voltage`) * 0.01667)) as decimal(9,2)) AS `prod_wh` from `inverter_data` group by 1;
DELIMITER $$
CREATE DEFINER=`solar`@`%` PROCEDURE `dashboard_infos`()
BEGIN
   DECLARE data_timestamp VARCHAR(12);
   DECLARE dummy VARCHAR(12);
   DECLARE weather_temp INT;
   DECLARE weather_icon INT;
   DECLARE weather_desc VARCHAR(60);
   DECLARE runtime_days INT;
   DECLARE inverter_status VARCHAR(1);
   DECLARE inverter_cntfaults INT;
   DECLARE inverter_cntwarnings INT;
   DECLARE inverter_cntfaultwarnings INT;
   DECLARE inverter_flagfaults INT;
   DECLARE stringa_voltage decimal(5,1);
   DECLARE stringa_current decimal(5,0);
   DECLARE rete_voltage decimal(5,1);
   DECLARE rete_frequency decimal(5,1);
   DECLARE batt_voltage decimal(5,1);
   DECLARE batt_voltageSCC decimal(5,1);
   DECLARE batt_chargeA decimal(5,0);
   DECLARE batt_dischageA decimal(5,0);
   DECLARE batt_capacity int(11);
   DECLARE batt_status varchar(30);
   DECLARE output_voltage decimal(5,1);
   DECLARE output_frequency decimal(5,1);
   DECLARE output_apparent_power decimal(5,0);
   DECLARE output_active_power decimal(5,0);
   DECLARE inverter_workingmode varchar(30);
   DECLARE inverter_workload int(11);
   DECLARE prod_instant decimal(5,0);
   DECLARE prod_peak decimal(5,0);
   DECLARE stor_today decimal(9,2);
   DECLARE stor_month decimal(9,2);
   DECLARE usage_today decimal(9,2);
   DECLARE usage_month decimal(9,2);
   DECLARE imp_today decimal(9,2);
   DECLARE imp_month decimal(9,2);
   DECLARE savings_month decimal(7,2);
   DECLARE savings_day decimal(7,2);
   DECLARE diodes_temp decimal(5,2);

   -- WEATHER INFOS --
   SELECT temperature,weather_id,weather_text INTO weather_temp,weather_icon,weather_desc FROM weather_data ORDER BY timestamp DESC LIMIT 1;
   -- RUNTIME DAYS --
   SELECT count(DISTINCT LEFT(timestamp,8)) INTO runtime_days FROM inverter_data;
   -- INVERTER STATUS --
   SELECT (fault_InverterFault+0),
      (fault_BusOver+fault_BusUnder+fault_BusSoftFail+fault_InverterVoltageTooLow+fault_InverterVoltageTooHigh+fault_InverterOverCurrent+fault_InverterSoftFail+fault_SelfTestFail+fault_OPDCVoltageOver+fault_BatOpen+fault_CurrentSensorFail+fault_BatteryShort),
      (warning_LineFail+warning_OPVShort+warning_BatteryLowAlarm+warning_BatteryUnderShutdown+warning_EepromFault+warning_PowerLimit+warning_PVVoltageHigh+warning_MPPTOverloadFault+warning_MPPTOverloadWarning+warning_BatteryTooLowToCharge),
      (faultwarning_OverTemperature+faultwarning_FanLocked+faultwarning_BatteryVoltageHigh+faultwarning_OverLoad)
      INTO inverter_flagfaults,inverter_cntfaults,inverter_cntwarnings,inverter_cntfaultwarnings
      FROM inverter_status ORDER BY timestamp DESC LIMIT 1;
   SELECT 'N' into inverter_status;
   SELECT IF((inverter_cntfaultwarnings > 0)and(inverter_flagfaults = 0), 'W',inverter_status) INTO inverter_status;
   SELECT IF(inverter_cntwarnings > 0, 'W',inverter_status) INTO inverter_status;
   SELECT IF((inverter_cntfaultwarnings > 0)and(inverter_flagfaults > 0), 'F',inverter_status) INTO inverter_status;
   SELECT IF(inverter_cntfaults > 0, 'F',inverter_status) INTO inverter_status;
   -- INVERTER DATA --
   SELECT timestamp, pv_voltage, pv_current, grid_voltage, grid_frequency,
		  battery_voltage, battery_voltage_scc, battery_charging_current, battery_discharge_current,
          battery_capacity, ac_output_voltage, ac_output_frequency, ac_output_apparent_power,
          ac_output_active_power, output_load_percent, (pv_voltage * pv_current),
          charge_description,status_description,diode_junction_temp
   INTO data_timestamp, stringa_voltage, stringa_current, rete_voltage,
		rete_frequency, batt_voltage, batt_voltageSCC, batt_chargeA, batt_dischageA,
        batt_capacity, output_voltage, output_frequency, output_apparent_power,
        output_active_power, inverter_workload, prod_instant,
        batt_status,inverter_workingmode,diodes_temp
   FROM inverter_data ORDER BY timestamp DESC LIMIT 1;
   -- CALCULATE POWER IMPORT/CONSUMPTION/PRODUCTION/STORAGE OF TODAY --
   SELECT LEFT(timestamp,8) AS 'timestamp',CAST(SUM(battery_charging_current*battery_voltage)*0.01667 AS DECIMAL (9, 2)) AS 'acc_wh',CAST(SUM(ac_output_active_power)*0.01667 AS DECIMAL (9, 2)) AS 'consumo_wh', CAST(SUM(IF(`inverter_data`.`status_code` = 'L', `inverter_data`.`ac_output_active_power`, 0)) * 0.01667 AS DECIMAL (9 , 2 )) AS 'import_wh' 
   INTO dummy, stor_today, usage_today, imp_today
   FROM inverter_data GROUP BY 1 ORDER BY timestamp DESC LIMIT 1;  
   SELECT CAST(MAX(pv_voltage*pv_current) AS DECIMAL(5,0)) AS 'prod_peak'
   INTO prod_peak
   FROM inverter_data WHERE (timestamp >= date_format(now(),'%Y%m%d0000'));
   -- CALCULATE POWER IMPORT/CONSUMPTION/STORAGE OF MONTH --
   SELECT LEFT(timestamp,8) AS 'timestamp',CAST(SUM(battery_charging_current*battery_voltage)*0.01667 AS DECIMAL (9, 2)) AS 'acc_wh',CAST(SUM(ac_output_active_power)*0.01667 AS DECIMAL (9, 2)) AS 'consumo_wh', CAST(SUM(IF(`inverter_data`.`status_code` = 'L', `inverter_data`.`ac_output_active_power`, 0)) * 0.01667 AS DECIMAL (9 , 2 )) AS 'import_wh' 
   INTO dummy,stor_month,usage_month,imp_month
   FROM inverter_data WHERE (timestamp >= date_format(now(),'%Y%m000000'));
   -- CALCULATE MONTHLY SAVINGS --
   SELECT CAST((SUM(IF(status_code<>'L',ac_output_active_power * 0.01667,0))/1000) *0.25 AS DECIMAL(7,2)) AS `prodcons_kwh` 
   INTO savings_month
   FROM inverter_data WHERE timestamp>=date_format(now(), '%Y%m000000');   
   -- CALCULATE DAILY SAVINGS --
   SELECT CAST((SUM(IF(status_code<>'L',ac_output_active_power * 0.01667,0))/1000) *0.25 AS DECIMAL(7,2)) AS `prodcons_kwh` 
   INTO savings_day
   FROM inverter_data WHERE timestamp>=date_format(now(), '%Y%m%d0000');   
   -- Create a tempoirary table to store results to present then drop it after using --
   CREATE TEMPORARY TABLE tmp (
		data_timestamp VARCHAR(12),
		weather_temp INT, 
        weather_icon INT, 
        weather_desc VARCHAR(60),
        runtime_days INT,
        inverter_status VARCHAR(1),
        pv_voltage decimal(5,1),
        pv_current decimal(5,0),
        grid_voltage decimal(5,1),
        grid_frequency decimal(5,1),
		battery_voltage decimal(5,1),
        battery_voltage_scc decimal(5,1),
		battery_charging_current decimal(5,0),
		battery_discharge_current decimal(5,0),
		battery_capacity int(11),
        battery_status varchar(30),
        ac_output_voltage decimal(5,1),
        ac_output_frequency decimal(5,1),
        ac_output_apparent_power decimal(5,0),
        ac_output_active_power decimal(5,0),
        inverter_mode varchar(30),
        inverter_load int(11),
        production_instant decimal(5,0),
        production_peak decimal(5,0),
        storage_today decimal(9,2),
        storage_month decimal(9,2),
        consumed_today decimal(9,2),
        consumed_month decimal(9,2),
        import_today decimal(9,2),
        import_month decimal(9,2),
        savings_month decimal(7,2),
        savings_day decimal(7,2),
        diode_junction_temp decimal(5,2)
	) ENGINE=MEMORY;
    INSERT INTO tmp VALUES (data_timestamp,weather_temp,weather_icon,weather_desc,
                            runtime_days,inverter_status,
                            stringa_voltage,stringa_current,
                            rete_voltage,rete_frequency,
                       		batt_voltage, batt_voltageSCC, batt_chargeA, batt_dischageA,
                            batt_capacity, batt_status,output_voltage, output_frequency,
                            output_apparent_power, output_active_power,
                            inverter_workingmode, inverter_workload,
                            prod_instant, prod_peak, stor_today, stor_month, usage_today,
                            usage_month, imp_today, imp_month, savings_month, savings_day,
                            diodes_temp);
	SELECT * FROM tmp;
    DROP TEMPORARY TABLE IF EXISTS tmp;

END$$
DELIMITER ;
