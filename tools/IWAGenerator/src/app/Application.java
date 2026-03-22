package app;
import businessobject.Station;
import businessobject.StationModel;
import generator.IGenerator;
import generatorhttp.GeneratorHTTP;

import java.io.File;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.TimeZone;

import ui.GeneratorGUI;
import util.DataLoader;
import util.SettingsFile;
import util.StationSelection;

public class Application
{
	private static Application instance = null;
	private File path;
	private Settings settings;
	private StationSelection stationSelection;
	private IGenerator generator;
	private GeneratorGUI generatorGui;
	private DataLoader dataLoader;

	public static Application getInstance() {
		if (instance == null) {
			instance = new Application();
		}
		return instance;
	}

	private Application() {
		this.path = new File((new File("")).getAbsoluteFile(), "");
		this.settings = new Settings(new SettingsFile(getLocalFile("settings.conf")));
		this.stationSelection = new StationSelection();
		instance = this;
		boolean logging = getInstance().getSettings().loggingEnabled();
		this.dataLoader = getInstance().initDataLoader(logging);	
		this.generator = getInstance().initGenerator(logging);
		this.generatorGui = new GeneratorGUI((IGenerator)this.generator);
	}

	private DataLoader initDataLoader(boolean logging) {
		if (logging) {
			System.out.println("Initializing data loader...");
		}
		DataLoader dataLoader = new DataLoader(getLocalFile("full_stations_data.dat"));
		dataLoader.start(Calendar.getInstance(TimeZone.getTimeZone("UTC")).get(6) - 1);		
		if (logging) {
			System.out.println("Done.");
			System.out.println();
		} 
		return dataLoader;
	}
	
	private ArrayList<StationModel> initDataModels(boolean logging) {
		if (logging) {
			System.out.println("Initializing data models...");
		}
		ArrayList<Station> stationList = this.dataLoader.getStations();
		ArrayList<StationModel> stationModels = new ArrayList<StationModel>();
		for (Station station : stationList) {
			StationModel stationModel = new StationModel(station, this.dataLoader);
			stationModels.add(stationModel);
		} 		
		if (logging) {
			System.out.println("Done.");
			System.out.println();
		} 
		return stationModels;
	}
	
	private IGenerator initGenerator(boolean logging) {
		ArrayList<StationModel> stationModels = getInstance().initDataModels(logging);
		if (logging) {
			System.out.println("Initializing generator...");
		} 
		IGenerator generator = new GeneratorHTTP(stationModels);
		if (logging) {
			System.out.println("Done.");
			System.out.println();
		}
		return generator;
	}

	public void exit() {
		this.settings.save();
		try {
			this.generator.stop();			
			while (this.generator.getActiveClusterCount() > 0) {
				Thread.sleep(100L);
				}
		} catch (InterruptedException e) {
			e.printStackTrace();
		} 
		this.generatorGui.exit();
		System.exit(0);
	}

	public Settings getSettings() {
		return this.settings;
	}

	public StationSelection getStationSelection() {
		return this.stationSelection;
	}

	public GeneratorGUI getGeneratorGui() {
		return this.generatorGui;
	}

	public File getLocalFile(String filename) {
		return new File(this.path, filename);
	}

	public static void main(String[] args) {
		getInstance();
	}
}
