import 'package:hive/hive.dart';
import '../../models/sync_queue_model.dart';
import '../../models/sync_metadata_model.dart';

void registerHiveAdapters() {
  if (!Hive.isAdapterRegistered(0)) {
    Hive.registerAdapter(SyncQueueModelAdapter());
  }
  if (!Hive.isAdapterRegistered(1)) {
    Hive.registerAdapter(SyncMetadataModelAdapter());
  }
}

class SyncQueueModelAdapter extends TypeAdapter<SyncQueueModel> {
  @override
  final int typeId = 0;

  @override
  SyncQueueModel read(BinaryReader reader) {
    return SyncQueueModel(
      id: reader.readString(),
      endpoint: reader.readString(),
      method: reader.readString(),
      data: reader.read(),
      headers: reader.readMap(),
      createdAt: DateTime.fromMillisecondsSinceEpoch(reader.readInt()),
      status: reader.readString(),
      retryCount: reader.readInt(),
      error: reader.readString(),
      updatedAt: DateTime.fromMillisecondsSinceEpoch(reader.readInt()),
    );
  }

  @override
  void write(BinaryWriter writer, SyncQueueModel obj) {
    writer.writeString(obj.id ?? '');
    writer.writeString(obj.endpoint ?? '');
    writer.writeString(obj.method ?? '');
    writer.write(obj.data);
    writer.writeMap(obj.headers ?? {});
    writer.writeInt(obj.createdAt?.millisecondsSinceEpoch ?? 0);
    writer.writeString(obj.status ?? '');
    writer.writeInt(obj.retryCount ?? 0);
    writer.writeString(obj.error ?? '');
    writer.writeInt(obj.updatedAt?.millisecondsSinceEpoch ?? 0);
  }
}

class SyncMetadataModelAdapter extends TypeAdapter<SyncMetadataModel> {
  @override
  final int typeId = 1;

  @override
  SyncMetadataModel read(BinaryReader reader) {
    return SyncMetadataModel(
      id: reader.readString(),
      entityType: reader.readString(),
      lastSyncTime: DateTime.fromMillisecondsSinceEpoch(reader.readInt()),
      syncStatus: reader.readString(),
      version: reader.readInt(),
      itemsSynced: reader.readInt(),
      createdAt: DateTime.fromMillisecondsSinceEpoch(reader.readInt()),
      updatedAt: DateTime.fromMillisecondsSinceEpoch(reader.readInt()),
    );
  }

  @override
  void write(BinaryWriter writer, SyncMetadataModel obj) {
    writer.writeString(obj.id ?? '');
    writer.writeString(obj.entityType ?? '');
    writer.writeInt(obj.lastSyncTime?.millisecondsSinceEpoch ?? 0);
    writer.writeString(obj.syncStatus ?? '');
    writer.writeInt(obj.version ?? 0);
    writer.writeInt(obj.itemsSynced ?? 0);
    writer.writeInt(obj.createdAt?.millisecondsSinceEpoch ?? 0);
    writer.writeInt(obj.updatedAt?.millisecondsSinceEpoch ?? 0);
  }
}