import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:hive/hive.dart';

part 'sync_metadata_model.freezed.dart';
part 'sync_metadata_model.g.dart';

@freezed
@HiveType(typeId: 1)
class SyncMetadataModel with _$SyncMetadataModel {
  const factory SyncMetadataModel({
    @HiveField(0) String? id,
    @HiveField(1) @JsonKey(name: 'entity_type') String? entityType,
    @HiveField(2) @JsonKey(name: 'last_sync_time') DateTime? lastSyncTime,
    @HiveField(3) @JsonKey(name: 'sync_status') String? syncStatus,
    @HiveField(4) int? version,
    @HiveField(5) @JsonKey(name: 'items_synced') int? itemsSynced,
    @HiveField(6) @JsonKey(name: 'created_at') DateTime? createdAt,
    @HiveField(7) @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _SyncMetadataModel;

  factory SyncMetadataModel.fromJson(Map<String, dynamic> json) => _$SyncMetadataModelFromJson(json);
}