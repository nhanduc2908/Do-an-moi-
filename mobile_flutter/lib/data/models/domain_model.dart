import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'domain_model.freezed.dart';
part 'domain_model.g.dart';

@freezed
class DomainModel with _$DomainModel {
  const factory DomainModel({
    String? id,
    String? name,
    String? code,
    String? description,
    @JsonKey(name: 'parent_id') String? parentId,
    int? level,
    int? weight,
    int? order,
    String? status,
    @JsonKey(name: 'created_at') DateTime? createdAt,
    @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _DomainModel;

  factory DomainModel.fromJson(Map<String, dynamic> json) => _$DomainModelFromJson(json);
}

extension DomainModelX on DomainModel {
  String get fullPath {
    final path = [name];
    // In real implementation, traverse parent hierarchy
    return path.join(' / ');
  }
}