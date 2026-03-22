package generatorhttp;
import java.util.ArrayList;
import java.util.Iterator;

import app.Application;
import util.RandomIterator;
import util.StationSelectionIterator;

public class ClientHTTPManager
{
	private ArrayList<ClientHTTP> clientList;

	public ClientHTTPManager(ArrayList<ClientHTTP> clientList) {
		this.clientList = clientList;
	}

	public void setActiveClients(int amount) {
		int activeClients = getActiveClusterCount();
		amount -= activeClients;
		if (amount < 0) {
			disconnectClients(Math.abs(amount));
		} else if (amount > 0) {
			connectClients(amount);
		}
	}

	public int getActiveClusterCount() {
		int count = 0;
		for (ClientHTTP client : this.clientList) {
			if (client.isActive()) {
				count++;
			}
		} 
		return count;
	}

	public int getDisabledClusterCount() {
		int count = 0;
		for (ClientHTTP client : this.clientList) {
			if (!client.isActive()) {
				count++;
			}
		} 
		return count;
	}

	public int getErrorClusterCount() {
		int count = 0;
		for (ClientHTTP client : this.clientList) {
			if (client.hasError()) {
				count++;
			}
		} 
		return count;
	}

	private void connectClients(int amount) {
		Iterator<ClientHTTP> clusterIterator;
		boolean useSelected = Application.getInstance().getSettings().useSelectedEnabled();
		if (useSelected) {
			clusterIterator = new StationSelectionIterator<ClientHTTP>(this.clientList, true);
		}
		else {
			clusterIterator = new RandomIterator<ClientHTTP>(this.clientList);
		} 
		while (clusterIterator.hasNext() && amount > 0) {
			ClientHTTP client = clusterIterator.next();
			if (!client.isActive()) {
				client.connect();
				amount--;
			} 
		} 				
	}

	private void disconnectClients(int amount) {
		Iterator<ClientHTTP> clusterIterator;
		boolean useSelected = Application.getInstance().getSettings().useSelectedEnabled();
		if (useSelected) {
			clusterIterator = new StationSelectionIterator<ClientHTTP>(this.clientList, false);
		}
		else {
			clusterIterator = new RandomIterator<ClientHTTP>(this.clientList);
		} 
		while (clusterIterator.hasNext() && amount > 0) {
			ClientHTTP client = clusterIterator.next();
			if (client.isActive()) {
				client.disconnect();
				amount--;
			} 
		} 				
	}
}