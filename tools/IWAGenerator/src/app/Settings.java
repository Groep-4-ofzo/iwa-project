package app;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;
import util.SettingsFile;

public class Settings
{
	private SettingsFile settingsFile;
	private static final String SETTING_HTTP_CLIENT_HOSTNAME = "client_http_hostname";
	private static final String SETTING_HTTP_CLIENT_PATH = "client_http_path";
	private static final String SETTING_HTTP_CLIENT_PORT = "8080";
	private static final String SETTING_MISSING_DATA_PROB = "1";
	private static final String SETTING_MIN_MISSING_DATA = "1";
	private static final String SETTING_PEAK_TEMP_PROB = "1";
	private static final String SETTING_MIN_PEAK_TEMP = "1";
	private static final String SETTING_STATS_UPDATE_INTERVAL = "1000";
	private static final String SETTING_STATION_UPDATE_INTERVAL = "30";
	private static final String SETTING_LOGGING = "true";
	private static final String SETTING_HTTP_LOGGING = "false";
	private static final String SETTING_CREATE_LIST = "false";
	private static final String SETTING_USE_SELECTED_STATIONS = "false";
	private static HashMap<String, String> defaultValues;
	private String httpHost;
	private String httpPath;
	private URL httpClient;
	private int httpPort;
	private int missingDataProbability;
	private int minimalMissingDataCount;
	private int peakTempProbability;
	private int minimalPeakTempCount;
	private int statsUpdateInterval;
	private int stationUpdateInterval;
	private boolean logging;
	private boolean httpLogging;
	private boolean createList;
	private boolean useSelected;
	

	public Settings(SettingsFile settingsFile) {
		defaultValues = new HashMap<String, String>();
		defaultValues.put("client_http_hostname", SETTING_HTTP_CLIENT_HOSTNAME);
		defaultValues.put("client_http_path", SETTING_HTTP_CLIENT_PATH);
		defaultValues.put("client_http_port", SETTING_HTTP_CLIENT_PORT);
		defaultValues.put("missing_data_prob", SETTING_MISSING_DATA_PROB);
		defaultValues.put("min_missing_data", SETTING_MIN_MISSING_DATA);
		defaultValues.put("peak_temp_prob", SETTING_PEAK_TEMP_PROB);
		defaultValues.put("min_peak_temp", SETTING_MIN_PEAK_TEMP);
		defaultValues.put("stats_update_interval", SETTING_STATS_UPDATE_INTERVAL);
		defaultValues.put("station_update_interval", SETTING_STATION_UPDATE_INTERVAL);
		defaultValues.put("logging", SETTING_LOGGING);
		defaultValues.put("http_logging", SETTING_HTTP_LOGGING);
		defaultValues.put("create_list", SETTING_CREATE_LIST);
		defaultValues.put("use_selected_stations", SETTING_USE_SELECTED_STATIONS);
		this.settingsFile = settingsFile;
		load();
	}

	private void load() {
		this.settingsFile.load();
		fillEmptyValues();
		this.httpHost = this.settingsFile.getValue("client_http_hostname");
		this.httpPath = this.settingsFile.getValue("client_http_path");
		this.httpPort = Integer.parseInt(this.settingsFile.getValue("client_http_port"));
		this.missingDataProbability = Integer.parseInt(this.settingsFile.getValue("missing_data_prob"));
		this.minimalMissingDataCount = Integer.parseInt(this.settingsFile.getValue("min_missing_data"));
		this.peakTempProbability = Integer.parseInt(this.settingsFile.getValue("peak_temp_prob"));
		this.minimalPeakTempCount = Integer.parseInt(this.settingsFile.getValue("min_peak_temp"));
		this.statsUpdateInterval = Integer.parseInt(this.settingsFile.getValue("stats_update_interval"));
		this.stationUpdateInterval = Integer.parseInt(this.settingsFile.getValue("station_update_interval"));
		this.logging = Boolean.parseBoolean(this.settingsFile.getValue("logging"));
		this.httpLogging = Boolean.parseBoolean(this.settingsFile.getValue("http_logging"));
		this.createList = Boolean.parseBoolean(this.settingsFile.getValue("create_list"));
		this.useSelected = Boolean.parseBoolean(this.settingsFile.getValue("use_selected_stations"));
	}

	public void save() {
		this.settingsFile.setValue("client_http_hostname", this.httpHost);
		this.settingsFile.setValue("client_http_path", this.httpPath);
		this.settingsFile.setValue("client_http_port", Integer.toString(this.httpPort));
		this.settingsFile.setValue("missing_data_prob", Integer.toString(this.missingDataProbability));
		this.settingsFile.setValue("min_missing_data", Integer.toString(this.minimalMissingDataCount));
		this.settingsFile.setValue("peak_temp_prob", Integer.toString(this.peakTempProbability));
		this.settingsFile.setValue("min_peak_temp", Integer.toString(this.minimalPeakTempCount));
		this.settingsFile.setValue("stats_update_interval", Integer.toString(this.statsUpdateInterval));
		this.settingsFile.setValue("station_update_interval", Integer.toString(this.stationUpdateInterval));
		this.settingsFile.setValue("logging", Boolean.toString(this.logging));
		this.settingsFile.setValue("http_logging", Boolean.toString(this.httpLogging));
		this.settingsFile.setValue("create_list", Boolean.toString(this.createList));
		this.settingsFile.setValue("use_selected_stations", Boolean.toString(this.useSelected));
		this.settingsFile.save();
	}

	private void fillEmptyValues() {
		for (Map.Entry<String, String> setting : defaultValues.entrySet()) {
			if (this.settingsFile.getValue(setting.getKey()) == null) {
				this.settingsFile.setValue(setting.getKey(), setting.getValue());
			}
		} 
	}

	public URL getHTTPClient() {
 		String urlString = "http://" + this.httpHost + ":" + this.httpPort +"/" + this.httpPath;
		try {
			this.httpClient = new URL(urlString);
		} catch (MalformedURLException e) {
			e.printStackTrace();
		}
		return this.httpClient;
	}
		
	public String getHTTPHost() {
		return this.httpHost;
	}

	public void setHTTPHost(String httpHost) {
		this.httpHost = httpHost;
	}

	public String getHTTPPath() {
		return this.httpPath;
	}

	public void setHTTPPath(String httpPath) {
		this.httpPath = httpPath;
	}

	public int getHTTPPort() {
		return this.httpPort;
	}

	public void setHTTPPort(int httpPort) {
		this.httpPort = httpPort;
	}

	public int getMissingDataProbability() {
		return this.missingDataProbability;
	}

	public void setMissingDataProbability(int missingDataProbability) {
		this.missingDataProbability = missingDataProbability;
	}

	public int getPeakTempProbability() {
		return this.peakTempProbability;
	}

	public void setPeakTempProbability(int peakTempProbability) {
		this.peakTempProbability = peakTempProbability;
	}

	public int getMinimalPeakTempCount() {
		return this.minimalPeakTempCount;
	}

	public void setMinimalPeakTempCount(int minimalPeakTempCount) {
		this.minimalPeakTempCount = minimalPeakTempCount;
	}

	public int getStatsUpdateInterval() {
		return this.statsUpdateInterval;
	}

	public void setStatsUpdateInterval(int statsUpdateInterval) {
		this.statsUpdateInterval = statsUpdateInterval;
	}

	public int getStationUpdateInterval() {
		return this.stationUpdateInterval;
	}

	public void setStationUpdateInterval(int stationUpdateInterval) {
		this.stationUpdateInterval = stationUpdateInterval;
	}

	public boolean loggingEnabled() {
		return this.logging;
	}

	public void setLoggingEnabled(boolean enabled) {
		this.logging = enabled;
	}

	public boolean httpLoggingEnabled() {
		return httpLogging;
	}

	public void setHttpLogging(boolean httpLogging) {
		this.httpLogging = httpLogging;
	}

	public boolean createListEnabled() {
		return createList;
	}

	public void setCreateList(boolean createList) {
		this.createList = createList;
	}

	public boolean useSelectedEnabled() {
		return useSelected;
	}

	public void setUseSelected(boolean useSelected) {
		this.useSelected = useSelected;
	}
}