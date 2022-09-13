import React, { useCallback } from 'react';
import { StyleSheet, Text, View, Modal, TouchableOpacity } from 'react-native';

export const OfflineModal = ({modalVisible, onPress}) => {
  const onTryAgainPress = useCallback(
    () => {
      onPress()
    },
    [onPress],
  )
  return (<Modal
        animationType="fade"
        transparent={true}
        visible={modalVisible}
      >
        <View style={{flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: 'rgba(0,0,0,0.5)'}}>
          <View style={styles.centeredView}>
            <View style={styles.modalView}>
              <Text style={styles.modalTextTitle}>Offline Mode</Text>
              <Text style={styles.modalText}>Please reconnect to the internet</Text>
              <Text style={styles.modalText}>and <Text style={{fontWeight: '600'}}>Try Again</Text>.</Text>
              <TouchableOpacity
                style={[styles.button, styles.buttonClose]}
                onPress={onTryAgainPress}
              >
                <Text style={styles.textStyle}>Try Again</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>)
}

const styles = StyleSheet.create({
  centeredView: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 22
  },
  modalView: {
    margin: 20,
    backgroundColor: "white",
    borderRadius: 20,
    padding: 35,
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: {
      width: 0,
      height: 2
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5
  },
  button: {
    borderRadius: 4,
    paddingHorizontal: 24,
    paddingVertical: 12,
    elevation: 2
  },
  buttonOpen: {
    backgroundColor: "#F194FF",
  },
  buttonClose: {
    backgroundColor: "#2196F3",
  },
  textStyle: {
    color: "white",
    fontWeight: "bold",
    textAlign: "center"
  },
  modalTextTitle: {
    fontWeight: 'bold',
    fontSize: 16,
    marginBottom: 15,
    textAlign: "center"
  },
  modalText: {
    marginBottom: 15,
    textAlign: "center"
  }
});
