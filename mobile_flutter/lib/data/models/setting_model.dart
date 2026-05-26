import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'setting_model.freezed.dart';
part 'setting_model.g.dart';

@freezed
class SettingModel with _$SettingModel {
  const factory SettingModel({
    String? id,
    @JsonKey(name: 'setting_key') String? key,
    @JsonKey(name: 'setting_value') dynamic value,
    @JsonKey(name: 'setting_type') String? type,
    String? category,
    String? description,
    @JsonKey(name: 'is_encrypted') bool? isEncrypted,
    @JsonKey(name: 'created_at') DateTime? createdAt,
    @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _SettingModel;

  factory SettingModel.fromJson(Map<String, dynamic> json) => _$SettingModelFromJson(json);
}

extension SettingModelX on SettingModel {
  String get stringValue => value?.toString() ?? '';
  int get intValue => int.tryParse(value?.toString() ?? '0') ?? 0;
  bool get boolValue => value == true || value == 'true' || value == '1';
  double get doubleValue => double.tryParse(value?.toString() ?? '0') ?? 0.0;
  List<String> get listValue {
    if (value is List) return List<String>.from(value);
    return [];
  }
}